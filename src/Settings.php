<?php


namespace App;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Settings
{
    private const SETTINGS_INDEX = 'app_settings';

    private ParameterBagInterface $params;

    private array $settings = [];

    /**
     * Settings constructor.
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->settings = $params->get(self::SETTINGS_INDEX);
    }

    public function get(string $index)
    {
        $key_chain = explode('.', $index);
        $return = $this->settings;
        foreach($key_chain as $key){
            $return = $return[$key];
        }

        return $return;
    }
}