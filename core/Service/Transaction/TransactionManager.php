<?php

namespace Core\Service\Transaction;

use Core\Dispatcher\EventDispatcherInterface;
use Core\Dispatcher\QueueDispatcherInterface;
use Core\Event\DomainEvent;

class TransactionManager
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private QueueDispatcherInterface $queueDispatcher,
    ){
    }

    /** @var null|callable */
    private $onProcessFunction = null;

    /** @var null|callable */
    private $onCompleteFunction = null;

    /** @var null|callable */
    private $onErrorFunction = null;

    public function begin(): Transaction
    {
        return new Transaction(\Yii::$app->db->beginTransaction());
    }

    public function onProcess(callable $function): self
    {
        $this->onProcessFunction = $function;
        return $this;
    }

    public function onComplete(callable $function): self
    {
        $this->onCompleteFunction = $function;
        return $this;
    }

    public function onError(callable $function): self
    {
        $this->onErrorFunction = $function;
        return $this;
    }

    public function run(bool $afterCommitProcess = true)
    {
        if (!$this->onProcessFunction) {
            throw new \RuntimeException('onProcess функция не установлена.');
        }

        $transaction = $this->begin();
        $context = new TransactionContext();

        try {
            $result = ($this->onProcessFunction)($context);
            $this->dispatcher->dispatchAll(DomainEvent::release());
            $transaction->commit();

            if ($afterCommitProcess) {
                $this->queueDispatcher->release();

                if ($this->onCompleteFunction) {
                    ($this->onCompleteFunction)($context);
                }
            }

            return $result;
        } catch (\Throwable $e) {
            $transaction->rollBack();

            if ($this->onErrorFunction) {
                ($this->onErrorFunction)($e, $context);
            }

            throw $e;
        } finally {
            $this->onProcessFunction = null;
            $this->onCompleteFunction = null;
            $this->onErrorFunction = null;
        }
    }
}
