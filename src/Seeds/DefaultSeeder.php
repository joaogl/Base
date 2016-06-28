<?php

class DefaultSeeder extends DatabaseSeeder
{

    public function run()
    {
        
        $admin = Sentinel::registerAndActivate(array(
            'email'       => 'admin@admin.com',
            'password'    => 'admin',
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'status'      => 1,
        ));

        $adminRole = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => array('admin' => 1),
        ]);

        Base::getSettingsRepository()->createModel()->create([
            'friendly_name'  => 'SITE_NAME',
            'name'  => 'Website name',
            'value'  => 'John Doe\'s website',
            'description'  => 'Website name',
        ]);

        Base::getSettingsRepository()->createModel()->create([
            'friendly_name'  => 'USER_REGISTRATION',
            'name'  => 'User registration status',
            'value'  => '1',
            'description'  => '/*
                                * 0 - Disabled
                                * 1 - Enabled and no activation
                                * 2 - User activation
                                * 3 - Admin activation
                                */',
        ]);

        $admin->roles()->attach($adminRole);

    }

}