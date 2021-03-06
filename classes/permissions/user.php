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
 * Date: 28.09.2016
 * Time: 21:29
 */
namespace iko\user\permissions;

use iko;
use iko\user\operators;
use iko\user\Permissions;
use iko\user\User as users;
use iko\Core as Core;


class User extends Permissions
{
	const id = users::id;
	const permissions = Permissions::user_permissions;
	const assignment = Permissions::user_assignment;

	private static $cache = array ();

	public static function get ($class, $reload = FALSE): Permissions
	{
		if ($class instanceof users) {
			$id = intval($class->get_id());
		}
		if (is_int($class)) {
			$id = $class;
		}
		if (is_string($class)) {
			$id = intval($class);
		}
		if (!isset(self::$cache[ $id ]) || self::$cache[ $id ] == NULL || $reload) {
			self::$cache[ $id ] = new User($id);

			return self::$cache[ $id ];
		}
		else {
			return self::$cache[ $id ];
		}
	}

	private $user_class;
	private $user_id;
	private $user_groups = array ();


	public function __construct ($user)
	{
		if ($user instanceof users) {
			$id = $user->get_id();
		}
		else {
			if (is_int($user)) {
				$id = $user;
			}
			if (is_string($user)) {
				$id = intval($user);
			}
		}
		$this->user_id = $id;
		$this->user_class = users::get($this->user_id);
		$this->load_groups();
		parent::__construct();
	}

	private function load_groups ()
	{
		$this->user_groups = $this->user_class->get_groups();
		$groups = Permissions::gets($this->user_groups);
		foreach ($groups as $group) {
			$perm_array = $group->get_permissions();
			foreach ($perm_array as $value) {
				$this->add_permission_value($value);
			}
		}
	}

	protected function load_permission ()
	{
		$sql = "SELECT * FROM " . self::user_permissions . " WHERE " . users::id . " = " . $this->user_class->get_Id();
		$statement = Core::PDO()->query($sql);
		$fetch_All = $statement->fetchAll();
		foreach ($fetch_All as $fetch) {
			$per = Value::get($fetch["permission_name"]);
			$this->add_permission_value($per);
		}
	}

	public function get_class (): iUser
	{
		return $this->user_class;
	}

}