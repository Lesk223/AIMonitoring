<?php
/*
** initMAX
** Copyright (C) 2021-2022 initMAX s.r.o.
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 3 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/

namespace Modules\ZabbixAI\Includes;

use Zabbix\Widgets\CWidgetField;
use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\Fields\CWidgetFieldSelect;
use Zabbix\Widgets\Fields\CWidgetFieldTextBox;
use Zabbix\Widgets\Fields\CWidgetFieldRadioButtonList;
use Zabbix\Widgets\Fields\CWidgetFieldSeverities;
use Zabbix\Widgets\Fields\CWidgetFieldTags;
use Zabbix\Widgets\Fields\CWidgetFieldCheckBoxList;
use Zabbix\Widgets\Fields\CWidgetFieldMultiSelectGroup;
use Zabbix\Widgets\Fields\CWidgetFieldMultiSelectOverrideHost;
use Zabbix\Widgets\Fields\CWidgetFieldMultiSelectHost;
use Modules\ZabbixAI\Widget;
/**
 * OpenAI widget form.
 */
class WidgetForm extends CWidgetForm {
/*
    protected function normalizeValues(array $values): array {
		$values = self::convertDottedKeys($values);

		return $values;
	}
*/
    public function addFields(): self {
        return $this
        ->addField(
				(new CWidgetFieldRadioButtonList('chat_type', _('Mode'), [
					Widget::TYPE_CHAT => _('Chat'),
					Widget::TYPE_REPORT => _('Report')
				]))->setDefault(Widget::TYPE_CHAT)
			)
      	->addField($this->isTemplateDashboard()
				? null
				: new CWidgetFieldMultiSelectGroup('groupids', _('Host groups'))
			)
			->addField($this->isTemplateDashboard()
				? null
				: new CWidgetFieldMultiSelectGroup('exclude_groupids', _('Exclude host groups'))
			)
			->addField($this->isTemplateDashboard()
				? null
				: new CWidgetFieldMultiSelectHost('hostids', _('Hosts'))
			)
			->addField(
				new CWidgetFieldTextBox('problem', _('Problem'))
			)
			->addField(
				new CWidgetFieldSeverities('severities', _('Severity'))
			)
     	->addField(
				new CWidgetFieldTextBox('problem', _('Problem'))
			)
      ->addField(
                (new CWidgetFieldTextBox('token', _('API Token')))
                    ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
            )
            
        ;
    }
}
