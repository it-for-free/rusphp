<?php

namespace ItForFree\rusphp\Common\Time;

/**
 * Класс для определения эффективного временного интервала между запросами
 * к некоторому API (напр. бесплатному).
 * Цель: с одной стороны проверить быстрее, с другой не попасть в бан.
 *  
 * Как это работает:
 * если при слишком частых запросах сервер перестаёт отвечать 
 * корреткно, то данный класс автоматически будет увеличивать интервал
 * в два раза, и уменьшать в два раза  при переходе 
 * от полосы некорректных ответом в корректным.
 * 
 * Возможно, что есть и более успешные стратегии, 
 * для которых класс можно унаследовать и переопределить методыю
 *
 *  (инервал времени, ожидание, пауза)
 */
class RequestsTimeInterval 
{
   /**
    * Начальный временной интервал
    * между запросами
    * 
    * @var int
    */
   public $startWaitInterval = 1;
   
    /**
    * Текущее время ожидания 
     * (автоматически обновляется объектом класса по внутренней логике)
    * 
    * @var int
    */
   protected $timeInterval = 1;
   
   /**
    * @param int $startWaitInterval стартовый интервал в секундах
    */
   public function __construct($startWaitInterval = 1) {
       $this->startWaitInterval = $startWaitInterval;
       $this->timeInterval = $startWaitInterval;
   }
   
   /**
    * Вызывайте, чтобы сделать паузу между запросами    
    */
   public function wait()
   {
       sleep($this->timeInterval);
   }
   
   /**
    * Вызывайте после очередного запроса, чтобы динамически обновить интервал
    * 
    * @param boolean $isLastResponceCorrect корректен ли 
    *    (с точки зрения внутренней логики вашего приложения)
    *    ответ последнего запроса, т.е. если его НЕ надо дублировать,
    *    то передавайте true.
    */
   public function update($isLastResponceCorrect)
   {
       $this->setNewIntervalValue($isLastResponceCorrect);
   }
   
   /**
    * 
    * @staticvar boolean $isPreviousResponceOk  Хранит статус завершения предыдущего 
    * (относительно того, для которого вы сейчас можете вызвать этот метод) зароса.
    * @param  boolean $isNewResponceOk  корректен ли 
    *    (с точки зрения внутренней логики вашего приложения)
    *    ответ последнего запроса, т.е. если его НЕ надо дублировать,
    *    то передавайте true.
    * @return null
    */
    protected function setNewIntervalValue($isNewResponceOk)
    {
       static $isPreviousResponceOk = true; // до начала работы будем считать что все ок)
       
       if ($isPreviousResponceOk && $isNewResponceOk) {
           return; // просто выходим, ничего не меняя, если продолжается удачная полоса
       } else if (!$isPreviousResponceOk && $isNewResponceOk)  {
           $this->timeInterval = intdiv($this->timeInterval, 2); // уменьшаем в 2 раза при переходе к удачной полосе
           $isPreviousResponceOk = $isNewResponceOk;
           return;
       } else if (!$isPreviousResponceOk && !$isNewResponceOk)  {
           $this->timeInterval = $this->timeInterval * 2; // если неудачная полоса продолжается, то увеличиваем в 2 раза.
           return;
       }
       
   }
}
