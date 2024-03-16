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

namespace Modules\YandexGPT\Includes;

use Zabbix\Widgets\CWidgetField;
use Zabbix\Widgets\CWidgetForm;
use Zabbix\Widgets\Fields\CWidgetFieldSelect;
use Zabbix\Widgets\Fields\CWidgetFieldTextBox;
use Zabbix\Widgets\Fields\CWidgetFieldRadioButtonList;

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
				(new CWidgetFieldRadioButtonList('evaltype', _('Problem tags'), [
					TAG_EVAL_TYPE_AND_OR => _('Chat'),
					TAG_EVAL_TYPE_OR => _('Report')
				]))->setDefault(TAG_EVAL_TYPE_AND_OR)
			)
      ->addField(
                (new CWidgetFieldTextBox('token', _('API Token')))
                    ->setFlags(CWidgetField::FLAG_NOT_EMPTY | CWidgetField::FLAG_LABEL_ASTERISK)
            )
            
        ;
    }
}
