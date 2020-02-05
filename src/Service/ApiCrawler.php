<?php

namespace App\Service;

class ApiCrawler
{
    const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';

    public function fetchFromDFAPI(): array
    {
        $data = array();

        $ch = curl_init(self::DEUTSCHE_FEIERTAGE_URL . '2020');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'X-DFA-Token: dfa')
        );

        $result = curl_exec($ch);
        $data = json_decode($result, true);
        if ($data['result'] == true) {
            foreach ($data['holidays'] as $key => $holiday) {
                $regions = [];
                foreach ($holiday['holiday']['regions'] as $region => $hasHoliday) {
                    $region = $region === 'bay' ? 'BY' : strtoupper($region);
                    if ($hasHoliday) {
                        $regions[] = $region;
                    }
                }
                $data['holidays'][$key]['holiday']['regions'] = $regions;
            }
            return $data['holidays'];
        }

        return [];
    }
}