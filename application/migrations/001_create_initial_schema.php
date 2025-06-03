<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_initial_schema extends CI_Migration
{

    public function up()
    {
        // access_logs
        $this->dbforge->add_field([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => FALSE, 'auto_increment' => TRUE],
            'timestamp'   => ['type' => 'DATETIME'],
            'ip_address'  => ['type' => 'VARCHAR', 'constraint' => 45],
            'user_agent'  => ['type' => 'TEXT'],
            'uri'         => ['type' => 'TEXT'],
            'method'      => ['type' => 'VARCHAR', 'constraint' => 10],
            'message'     => ['type' => 'TEXT'],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('access_logs');

        // groups
        $this->dbforge->add_field([
            'id'          => ['type' => 'MEDIUMINT', 'constraint' => 8, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'description' => ['type' => 'VARCHAR', 'constraint' => 100],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('groups');

        // guests
        $this->dbforge->add_field([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'nim'           => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'prodi'         => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'qr_code'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_checked_time' => ['type' => 'DATETIME'],
            'is_checked_in' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('guests');

        // login_attempts
        $this->dbforge->add_field([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45],
            'login'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'time'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('login_attempts');

        // migrations
        $this->dbforge->add_field([
            'version' => ['type' => 'BIGINT', 'constraint' => 20],
        ]);
        $this->dbforge->create_table('migrations');

        // settings
        $this->dbforge->add_field([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'company'     => ['type' => 'VARCHAR', 'constraint' => 200],
            'address'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'telepon'     => ['type' => 'VARCHAR', 'constraint' => 15],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'logo'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('settings');

        // users
        $this->dbforge->add_field([
            'id'                         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'ip_address'                 => ['type' => 'VARCHAR', 'constraint' => 45],
            'username'                   => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'password'                   => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'                      => ['type' => 'VARCHAR', 'constraint' => 254],
            'activation_selector'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'activation_code'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'forgotten_password_selector' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'forgotten_password_code'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'forgotten_password_time'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'remember_selector'          => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'remember_code'              => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
            'created_on'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'last_login'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
            'active'                     => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => TRUE, 'null' => TRUE],
            'first_name'                 => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
            'last_name'                  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
            'company'                    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
            'phone'                      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE],
            'file_path'                  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('email', TRUE);
        $this->dbforge->add_key('activation_selector', TRUE);
        $this->dbforge->add_key('forgotten_password_selector', TRUE);
        $this->dbforge->add_key('remember_selector', TRUE);
        $this->dbforge->create_table('users');

        // users_groups
        $this->dbforge->add_field([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'user_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
            'group_id' => ['type' => 'MEDIUMINT', 'constraint' => 8, 'unsigned' => TRUE],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['user_id', 'group_id'], TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('group_id');
        $this->dbforge->create_table('users_groups');

        // user_logs
        $this->dbforge->add_field([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => TRUE],
            'user_id'   => ['type' => 'INT', 'constraint' => 11],
            'action'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'timestamp' => ['type' => 'DATETIME'],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user_logs');

        // Tambahkan foreign key (secara manual karena dbforge tidak support langsung)
        $this->db->query('ALTER TABLE `users_groups` ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;');
        $this->db->query('ALTER TABLE `users_groups` ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_logs', TRUE);
        $this->dbforge->drop_table('users_groups', TRUE);
        $this->dbforge->drop_table('users', TRUE);
        $this->dbforge->drop_table('settings', TRUE);
        $this->dbforge->drop_table('migrations', TRUE);
        $this->dbforge->drop_table('login_attempts', TRUE);
        $this->dbforge->drop_table('guests', TRUE);
        $this->dbforge->drop_table('groups', TRUE);
        $this->dbforge->drop_table('access_logs', TRUE);
    }
}
