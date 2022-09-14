<?php

namespace usertype;

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Iblock;

class CUserTypeTest
{
    public function GetUserTypeDescription()
    {
        return array(
            'USER_TYPE_ID' => 'user_test',
            'USER_TYPE' => 'TEST',
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Тестовое свойство',
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
        );
    }

    public static function ConvertToDB($arProperty, $value)
    {
        if ($value['VALUE']['TITLE'] != '' && $value['VALUE']['TEXT'] != '')
        {
            try {
                $value['VALUE'] = base64_encode(serialize($value['VALUE']));
            } catch(Bitrix\Main\ObjectException $exception) {
                echo $exception->getMessage();
            }
        } else {
            $value['VALUE'] = '';
        }

        return $value;
    }

    public static function ConvertFromDB($arProperty, $value, $format = '')
    {
        if ($value['VALUE'] != '')
        {
            try {
                $value['VALUE'] = base64_decode($value['VALUE']);
            } catch(Bitrix\Main\ObjectException $exception) {
                echo $exception->getMessage();
            }
        }

        return $value;
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $arHtmlControl)
    {
        $itemId = 'row_' . substr(md5($arHtmlControl['VALUE']), 0, 10);
        $fieldName =  htmlspecialcharsbx($arHtmlControl['VALUE']);

        $arValue = unserialize(htmlspecialcharsback($value['VALUE']), [stdClass::class]);

        $html = '<div id="'. $itemId .'">';

        $html .= '<div style="display:flex;align-items:flex-start;">';
        $title = ($arValue['TITLE']) ? $arValue['TITLE'] : '';
        $text = ($arValue['TEXT']) ? $arValue['TEXT'] : '';

        $html .='<input type="text" name="'. $fieldName .'[TITLE]" value="'. $title . '">';
        $html .='&nbsp;&nbsp;&nbsp;<textarea name="'. $fieldName .'[TEXT]">'. $text .'</textarea>';
        if($title!='' && $text!=''){
            $html .= '&nbsp;&nbsp;<input type="button" style="height: auto;" value="x" title="Удалить" onclick="document.getElementById(\''. $itemId .'\').parentNode.parentNode.remove()" />';
        }
        $html .= '</div>';

        $html .= '</div><br/>';

        return $html;
    }
}
