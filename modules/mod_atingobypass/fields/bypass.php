<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldBypass extends JFormField {

    protected $type = 'Bypass';

    // getLabel() left out


    public function getInput() {

        $document = JFactory::getDocument();
        $document->addScript(JURI::root(true) . '/administrator/modules/mod_atingobypass/js/String.UTF-8.js');
        $document->addScript(JURI::root(true) . '/administrator/modules/mod_atingobypass/js/String.Base64.js');
        $document->addScript(JURI::root(true) . '/administrator/modules/mod_atingobypass/js/atingobypass.js');

        $document->addScriptDeclaration('
            window.addEvent("domready", function() {
                new AtingoPassForm({
                    storage: "'.$this->id.'",
                    list: "atingopass-list"
                });
            });
        ');


        $html =<<<HTML
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
<style>div.panel-atingobypass dt {clear: both;} table#atingopass-list {border-spacing: 0;width:100%;border-top: 1px
solid #ccc;border-left: 1px solid #ccc;}table#atingopass-list td, table#atingopass-list th {border-right: 1px solid
#eee;border-bottom: 1px solid #eee;padding:4px;}table#atingopass-list th{background-color: #fafafa;}</style>
<div class="clr"></div>
<div class="panel-atingobypass">
    <table id="atingopass-list">
        <caption>List de sitios con acceso AtingoBypass</caption>
        <thead>
            <tr>
                <th>Nome do sitio</th>
                <th>URL</th>
                <th>Token</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <br class="clr"/>

    <dl>
        <dt>Nome do sitio:</dt>
        <dd>
            <input type="hidden" id="ap-id" value=""/>
            <input type="text" id="ap-sitename" value="" placeholder="Site name"/>
        </dd>
        <dt>URL:</dt>
        <dd><input type="text" id="ap-url" value="" placeholder="http://site.com/administrator/index.php"/></dd>
        <dt>Token:</dt>
        <dd>
            <input type="text" id="ap-token" value="" placeholder="Secret token"/>
            <i id="ap-keygen" class="fa fa-retweet"></i>
        </dd>
        <dd>
            <div class="clr"></div>
            <button id="ap-button">Gardar</button>
        </dd>
    </dl>

    <input type="hidden" name="{$this->name}" id="{$this->id}" value="{$this->value}" />
</div>
HTML;

        return $html;
    }
}