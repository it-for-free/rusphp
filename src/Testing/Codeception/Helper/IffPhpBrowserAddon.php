<?php
namespace ItForFree\rusphp\Testing\Codeception\Helper;

use ItForFree\rusphp\DOM\IffDomElement as DOM;
use \Codeception\Util\HttpCode;

/**
 * Расширение к codeseption PhpBrowser
 * (от которого данный класс и зависит)
 */
class IffPhpBrowserAddon extends \Codeception\Module
{
    
    use IffCodeceptionConsoleLoggerTrait;
    
    
    /**
     * Поиск по селектору с использованием PhpBrowser
     * @todo лучше не используйте этот метод напрямую в тестах, 
     * а используйте в других тестирующих методах
     * 
     * @param  string $selector
     * @return array of interactive elements
     */
    public function findBySelector($selector) 
    {
        return $this->getModule('PhpBrowser')->_findElements($selector);
    }
    
    
    /**
     * Проверит, что элемент (input) доступен для редаткирования
     * (т.е. "не disabled")
     * 
     * @param string $inputSelector
     */
    public function seeInputIsEditable($inputSelector)
    {
        
        $I = $this;
        $result = $this->findBySelector($inputSelector);
        
       // $this->pre($inputSelector, 'Ищем по селектору');
       // $this->pre($result->count(), 'Найдено элементов');

        foreach ($result as $element) {
            $disabledAttrValue = $element->getAttribute('disabled');
            $I->assertTrue(DOM::isEditable($element), "Элемент  " . $element->getAttribute('name') 
                    . " доступен для редактирования, "
                    . " значиение атрибута disabled=[$disabledAttrValue]"
                    . " readonly=[" . $element->getAttribute('readonly') . "]");            
        } 
    }
    
    /**
     * Все элементы формы достпуны для редактирования
     * 
     * @param string $formSelector
     * @param array $exeptNames  массив имен полей-исключения, которые не доступны
     */
    public function seeFormIsEditable(string $formSelector, array $exeptNames = [])
    {
        
        $I = $this;
        
        $allFormElementsSelector = $formSelector . " input,"
                 . $formSelector . " textarea,"
                . $formSelector . " select";
                
        $result = $this->findBySelector($allFormElementsSelector);
        
       // $this->pre($allFormElementsSelector, 'Ищем по селектору');
       // $this->pre($result->count(), 'Найдено элементов');

        foreach ($result as $element) {
            $elementName = $element->getAttribute('name');
            // $this->pre($elementName, 'Проверяем элемент ' . $element->tagName);
            
            $disabledAttrValue = $element->getAttribute('disabled');

            
            $checkResult = DOM::isEditable($element)
                    || (!DOM::isEditable($element) && in_array($elementName, $exeptNames));
            
            $no = '';
            if (in_array($elementName, $exeptNames))
            {
                $no = 'не';
            }
            
            $I->assertTrue($checkResult, 
                    "Элемент  " . $elementName 
                    . " $no доступен для редактирования, "
                    . " значиение атрибута disabled=[$disabledAttrValue]"
                    . " readonly=[" . $element->getAttribute('readonly') . "]");                    
        } 
    }
    
    /**
     * Проверит, что вся форма недоступна (за искл, нескольких полей)
     * 
     * @param string   $formSelector
     * @param array    $exeptNames  имена доступных для редактировния полей
     * @param callable $exeptNames  функция обработного вызова принимает имя поля и возвращает 
     */
    public function dontSeeFormIsEditable(string $formSelector, 
            array $exeptNames = [], callable $safeNameCheckFunction = null)
    {  
        $I = $this;

        $allFormElementsSelector = $formSelector . " input,"
                 . $formSelector . " textarea,"
                . $formSelector . " select";
        
      //  $allFormElementsSelector = "#primary-report-form input, #primary-report-form textarea, #primary-report-form select";
                
        $result = $this->findBySelector($allFormElementsSelector);
        
//        $this->pre($allFormElementsSelector, 'Ищем по селектору');
//        $this->pre($result->count(), 'Найдено элементов');
        
        
        foreach ($result as $element) {      
            $elementName = $element->getAttribute('name');
           // $this->pre($elementName, 'Проверяем элемент ' . $element->tagName);
            $disabledAttrValue = $element->getAttribute('disabled'); 
            $checkResult = !DOM::isEditable($element)
                    || (DOM::isEditable($element) && in_array($elementName, $exeptNames));
            
            if ($checkResult || !empty($safeNameCheckFunction)) { 
                 $checkResult = ($checkResult || $safeNameCheckFunction($elementName));           
            }
            
            
            $no = 'не';
            if (in_array($elementName, $exeptNames))
            {
                $no = '';
            }
            
            $I->assertTrue($checkResult, 
                    "Элемент  " . $elementName 
                    . " $no доступен для редактирования, "
                    . " значиение атрибута disabled=[$disabledAttrValue]"
                    . " readonly=[" . $element->getAttribute('readonly') . "]");                    
        } 
    }
    
    /**
     * Получит  значение поля формы по его имени (атрибут name)
     * (проверит, что нет дублирования)
     * 
     * ВНИМАНИЕ: выберет значение для первого типа поля, напр, input --
     * -- если таких элементов не 0, то остальные типа (select и textarea
     * проверяться не будут), в случае если их > 1 будет брошено исключение,
     * предупреждающее, что лучше использовать какое-то другое решение.
     * 
     * @todo можно разобраться с неоднозначностью выборки для некоторых элементов-
     * - см.  elseif ($searachResult->count() > 1) { // неоднозначность выборки
     * 
     * @param string $filedNameAttrValue имя поля формы
     * @param string $formSelector       селектор формы
     * @return string
     * @throws \Exeption
     */
    public function grabFormFieldValue(string $filedNameAttrValue, string $formSelector = '')
    {
        
        $tags = DOM::getFormFiledTagsList();
        
        foreach ($tags as $tag) {
            $elementSelector = "$formSelector " . $tag . "[name='$filedNameAttrValue'] ";
            $searachResult = $this->findBySelector($elementSelector);
            
            if ($searachResult->count() == 1) {
                break;
            } elseif ($searachResult->count() > 1) { // неоднозначность выборки
                throw new \Exeption("grabFormField: you have more that one field for selector '$elementSelector' "
                        . " -- please use  PhpBrowser's I->grabAttributeFrom() directly to avoid"
                        . " uncertainty.");
            }
        }
        
        if ($searachResult->count() != 1) {
               throw new \Exeption("Cant find filed twith name '$filedNameAttrValue' "
                       . "form form with selector '$formSelector'. "
                       . "Last attention selector was: '$elementSelector' ");
        } 
        
        $value  = $this->grabValue($elementSelector);
       // $this->pre("Извлекаем значение '$value'  по селектору $elementSelector");
        return $value;     
    }
    
    /**
     * Обёртка для  PhpBrowser->grabValueFrom()
     * -- т.е вытащит значение и для textarea
     * 
     * @param string $selector
     * @return string
     */
    public function grabValue($fieldElementSelector)
    {
        return $this->getModule('PhpBrowser')->grabValueFrom($fieldElementSelector);
    }
    
   
    /**
     * Вернёт значения всех полей формы в виде ассоциативного массива
     * 
     * @param string $formSelector  селектор формы
     * @return array ассоциативный массив. ключ -- name поля формы, а значение -- значение
     */
    public function grabAllFormValues($formSelector)
    {
        $allFormElementsSelector = $formSelector . " input,"
            . $formSelector . " textarea,"
            . $formSelector . " select";
        
        
        $result = $this->findBySelector($allFormElementsSelector);
        
//        $this->pre($allFormElementsSelector, 'Ищем по селектору');
//        $this->pre($result->count(), 'Найдено элементов');
        
        $formData = [];
        foreach ($result as $element) {      
            $elementName = $element->getAttribute('name');
            $elementSelector = $formSelector
                . " " . $element->tagName 
                    . "[name='$elementName']" ;
            
            $formData[$element->getAttribute('name')] =
                $this->grabValue($elementSelector);       
        }
        
        return $formData;
    }
    
    /**
     * ОТправит данные формы на указанный url
     * методом POST 
     * 
     * @param string $url
     * @param string $formSelector
     * @param array $newFormData  дополнительные значения (или замена для значения по-умолчанию)
     */
    public function submitFormToUrlByPost($url, $formSelector, $newFormData = [])
    {
        $I = $this;
        $oldFormData = $this->grabAllFormValues($formSelector);
        $I->getModule('REST')->sendPOST($url, array_merge($oldFormData, $newFormData));
    }
    
    /**
     * Проверит есть ли такой/такие DOM html элемент на странице --
     *  по селектору
     * 
     * @param  string $selector  селектор
     */
    public function seeDOMElement($selector)
    {
        $I = $this;
        $I->assertTrue($this->hasDOMElement($selector), 
                "DOM Элемент/элементы соотв. селектору"
                . " '$selector' присутствуют на странице ");
    }
    
    /**
     * Проверит есть ли такой/такие DOM html элемент на странице --
     *  по селектору
     * 
     * @param  string $selector  селектор
     * @return boolean
     */
    protected function hasDOMElement($selector)
    {
        $result = $this->findBySelector($selector);  
        return ($result->count() > 0);
       
    }
    
    
    /**
     * Проверит что HTTP код ответа ("данной страницы") = 200
     * т.е. что не т сообещния о проблеме.
     */
    public function seeStatusOk()
    {
        $this->getModule('PhpBrowser')->seeResponseCodeIs(HttpCode::OK);
    }
    
    
}

