<?php

namespace ItForFree\rusphp\DOM;

/**
 * Разные операции для стандартного \DOMElement
 *
 * @author 
 */
class IffDomElement {
    
   /**
    * Проверит доступность для редактирования
    * (т.е. что элмент не "disabled" и нет readonly)
    * 
    * значение атрибутов при этом не важно -- если хоть
    * один есть даже пустой -- то false
    * 
    * @param \DOMElement $element
    * @return boolean редактируемо ли данное поле
    */
   public static function isEditable(\DOMElement $element)
   {
       return (!$element->hasAttribute('disabled'))
               && (!$element->hasAttribute('readonly'));
   }
   
   /**
    * Список тегов полей ввода html формы
    * -- все возможные
    * 
    * @return array
    */
   public static function getFormFiledTagsList()
   {
       return ['input', 'textarea', 'select'];
   }
}
