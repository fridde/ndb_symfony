<?php


namespace App\Entity;


trait DefaultSerializable
{
    public function getStandardMembers(): array
    {
        $return = [];

        $standard_members = $this->standard_members ?? [];

        foreach($standard_members as $member){
            $return[$member] = $this->{$member};
        }
        return $return;
    }
}