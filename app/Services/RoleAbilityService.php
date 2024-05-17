<?php

namespace App\Services;

class RoleAbilityService
{
    public function getAbilitiesForRole($role)
    {
        $abilitiesConfig = config('constants.abilities');

        switch ($role) {
            case 'Admin':
                return ['*'];

            case 'Agente':
                return $this->getAbilitiesForResources($abilitiesConfig, ['aseguradoras', 'clientes', 'polizas']);

            case 'Gerente':
                return $this->getAbilitiesForResources($abilitiesConfig, ['usuarios']);

            default:
                return [];
        }
    }

    private function getAbilitiesForResources($abilitiesConfig, $resources)
    {
        $abilities = [];

        foreach ($resources as $resource) {
            if (isset($abilitiesConfig[$resource])) {
                $abilities = array_merge($abilities, array_values($abilitiesConfig[$resource]));
            }
        }

        return $abilities;
    }
}
