<?php declare(strict_types = 0);

use Zabbix\Widgets\Fields\CWidgetFieldTextBox;
 (new CWidgetFormView($data))
    ->addField(
        new CWidgetFieldTextBoxView($data['fields']['token'])
    )
   ->addField(
		new CWidgetFieldRadioButtonListView($data['fields']['evaltype'])
	)
    ->addItem([new CDiv('Contact with me:'), new CDiv(new CLink('https://t.me/voolkov7'))])
    ->includeJsFile('widget.edit.js.php')
    ->addJavaScript('widget_openai_form.init();')
    
	->show();
