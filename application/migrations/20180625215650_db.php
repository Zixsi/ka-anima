<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_db extends CI_Migration
{
	public function up()
	{
		/*CREATE TABLE `teaching`.`users` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `email` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' , `hash` INT NOT NULL , `ts_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `ts_modify` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;*/
		
		$this->dbforge->add_field([
				'id' => [
					'type' => 'INT',
					'unsigned' => TRUE,
					'auto_increment' => TRUE
				],
				'email' => [
					'type' => 'VARCHAR',
					'constraint' => 255
				],
				'password' => [
					'type' => 'VARCHAR',
					'constraint' => 255
				],
				'active' => [
					'type' => 'TINYINT',
					'constraint' => 1,
					'unsigned' => TRUE,
					'default' => 0
				],
				'hash' => [
					'type' => 'VARCHAR',
					'constraint' => 255
				],
				'ts_created' => [
					'type' => 'TIMESTAMP',
					'default' => 'CURRENT_TIMESTAMP'
				],
				'ts_modify' => [
					'type' => 'TIMESTAMP',
					'default' => 'CURRENT_TIMESTAMP'
				],
		]);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('users');
	}

	public function down()
	{
		$this->dbforge->drop_table('users');
	}
}