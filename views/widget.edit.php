<?php declare(strict_types = 0);

use Zabbix\Widgets\Fields\CWidgetFieldTextBox;

$groupids = array_key_exists('groupids', $data['fields'])
	? new CWidgetFieldMultiSelectGroupView($data['fields']['groupids'])
	: null;
 (new CWidgetFormView($data))
    ->addField(
        new CWidgetFieldTextBoxView($data['fields']['token'])
    )
   ->addField(
		new CWidgetFieldRadioButtonListView($data['fields']['chat_type'])
	)
  ->addField(($groupids)->addRowClass('js-row-show'),)
  ->addField(array_key_exists('hostids', $data['fields'])
		? (new CWidgetFieldMultiSelectHostView($data['fields']['hostids']))->addRowClass('js-row-show')
			->setFilterPreselect(['id' => $groupids->getId(), 'submit_as' => 'groupid'])
		: null
	)
 ->addField(
		(new CWidgetFieldTextBoxView($data['fields']['problem']))->addRowClass('js-row-show'),
	) 
	->addField(
		(new CWidgetFieldSeveritiesView($data['fields']['severities']))->addRowClass('js-row-show'),
	)
    ->addItem([new CDiv('Contact with me:'), new CDiv(new CLink('https://t.me/voolkov7'))])
    ->includeJsFile('widget.edit.js.php')
    ->addJavaScript('widget_openai_form.init();')
	  ->show();
