<?php

namespace ItForFree\rusphp\Common\Time;

/**
 * Планировщик временных интервалов между запросами.
 * 
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
class RequestsTimeInterval implements RequestsTimeIntervalInterface
{
   /**
    * @var int Очередной ожидаемый интервал между запросами (по факту именно это значение испольуется для ожидания)
    */
   protected $timeInterval;
   
   /**
    * @var int Cтолько секунд добавим к интервалу после удачного запроса, если предыдущий запрос был неудачным
    */
   protected $additionalToBtwSuccess = 1;
   
   /**
    *
    * @var int столько секунд добавим к интервалу между неудачными запрсаи, после каждого неудачного 
    */
   protected $additionalToBtwFails = 60;
     
   /**
    * Текущее время ожидания между удачными запросами
    * (автоматически обновляется объектом класса по внутренней логике)
    * 
    * @var int
    */
   protected $timeIntervalBtwSuccess;
   
   /**
    * @var int  Текущее время ожидания  в секундах между неудачными запросами
    */
   protected $timeIntervalBtwFails;
   
   /**
    * Минимальное время в секундах, которое надо выставлять,
    *  в случае елси раньше интервал был = 0
    * @var int 
    */
   public $minimumNotZero = 1;
   
   /**
    * 
    * @param int $timeIntervalBtwSuccess  начальное значение в секундах для ожидания после удачного запроса
    * @param int $timeIntervalBtwFails   начальное значение в секундах для ожидания после неудачного запроса
    * @param int $additionalToBtwSuccess столько секунд добавим к интервалу между удачными запросами, после выхода из поломы неудачных запросов
    * @param int $additionalToBtwFails  столько секунд добавим к интервалу между неудачными запрсаи, после каждого неудачного 
    */
   public function __construct(
           $timeIntervalBtwSuccess = 1,
           $timeIntervalBtwFails = 60,
           $additionalToBtwSuccess = 1,
           $additionalToBtwFails = 60) {
       $this->timeInterval = $timeIntervalBtwSuccess;
       
       $this->timeIntervalBtwSuccess = $timeIntervalBtwSuccess;
       $this->timeIntervalBtwFails = $timeIntervalBtwFails;
       $this->additionalToBtwSuccess = $additionalToBtwSuccess;
       $this->additionalToBtwFails = $additionalToBtwFails;
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
    * Обновит интервал очередного ожидания по ситуации
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
           $this->timeIntervalBtwSuccess += $this->additionalToBtwSuccess;
           $this->timeInterval = $this->timeIntervalBtwSuccess; 
       } else if ($isPreviousResponceOk && !$isNewResponceOk)  {
           $this->timeInterval = $this->timeIntervalBtwFails;
       } else if (!$isPreviousResponceOk && !$isNewResponceOk)  {
           $this->timeIntervalBtwFails += $this->additionalToBtwFails;
           $this->timeInterval = $this->timeIntervalBtwFails;
       }
       
       $isPreviousResponceOk = $isNewResponceOk;
   }
   
   /**
    * Если интервал был равен нулю, то для умножения, 
    * нам потребуется установить некое отлично от нуля число из поля 
    * класса minimumNotZero
    */
   protected function setMinimumIntervalIfZero()
   {
        if (!$this->timeInterval) {
            $this->timeInterval = $this->minimumNotZero; 
        }
   }

   /**
    * Вернёт текущее значение паузы (для ближайшего запроса)
    * @return int
    */
   public function getCurrentInterval()
   {
       return $this->timeInterval;
   }
}
