<?php

namespace ItForFree\rusphp\Common\Time;

/**
 * Интерфейс планировщик временных интервалов между запросами.
 * 
 */
interface RequestsTimeIntervalInterface 
{
    
   /**
    * Вызывайте, чтобы сделать паузу между запросами    
   */
   public function wait();
   
   /**
    * Вызывайте после очередного запроса, чтобы динамически обновить интервал
    * 
    * @param boolean $isLastResponceCorrect корректен ли 
    *    (с точки зрения внутренней логики вашего приложения)
    *    ответ последнего запроса, т.е. если его НЕ надо дублировать,
    *    то передавайте true.
    */
   public function update($isLastResponceCorrect);
   
   /**
    * Вернёт текущее значение паузы (для ближайшего запроса)
    * @return int
    */
   public function getCurrentInterval();
}
