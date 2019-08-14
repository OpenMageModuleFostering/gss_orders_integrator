<?php
/**
 * GSS
 *
 * NOTICE OF LICENSE
 *
 * This file is part of Order Integration.
 *
 * Foobar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *  
 * Order Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @category    GSS
 * @package     GSS_OrdersIntegration
 * @copyright   Copyright (c) 2016 SweetSpot Group Ltd (http://www.gosweetspot.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
\Magento\Framework\Component\ComponentRegistrar::register(
		\Magento\Framework\Component\ComponentRegistrar::MODULE,
		'GSS_OrdersIntegration',
		__DIR__
		);