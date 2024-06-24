<?php declare(strict_types = 0);
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
use Modules\YandexGPT\Widget;
?>

window.widget_openai_form = new class {

    init() {
   	this._form = document.getElementById('widget-dialogue-form');
    this._chat_type = document.getElementById('chat_type');
    for (const checkbox of this._chat_type.querySelectorAll('input')) {
			checkbox.addEventListener('change', () => this.updateForm());
		}        
    this.updateForm();
    }
   	updateForm() {
		const is_digital = this._chat_type.querySelector('input:checked').value == <?= Widget::TYPE_REPORT ?>;

		for (const element of this._form.querySelectorAll('.js-row-show')) {
			element.style.display = is_digital ? '' : 'none';
		}
	
			}
		
     
};
