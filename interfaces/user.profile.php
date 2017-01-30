<?php
/**
 *
 * This file is part of IkoBB Forum and belongs to the module <User>.
 *
 * @copyright (c) IkoBB <https://www.ikobb.de>
 * @license       GNU General Public License, version 3 (GPL-3.0)
 *
 * For full copyright and license information, please see
 * the LICENSE file.
 *
 */
/**
 * Created by PhpStorm.
 * User: Marcel
 * Date: 16.12.2016
 * Time: 21:48
 */
namespace iko\user;


use iko\user\profile\iContent;

interface iUser_profile
{
	const profiles = "{prefix}user_profiles";
	const fields = "{prefix}user_fields";

	public function get (string $name): iContent;

	public function set (string $name, $value): bool;

	public function create (iContent $item): bool;
}