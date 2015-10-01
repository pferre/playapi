<?php


namespace AppBundle\Tests\Traits;


trait Payloads
{
    /**
     * @return string
     */
    public function createJSONPayloadForNewArticle()
    {
        $payload = [
            'title' => "Creates a new article",
            'body' => "Just a body for the newly created article."
        ];

        $json = json_encode($payload, true);

        return $json;
    }

    /**
     * @return string
     */
    public function createJSONPayloadForUpdateArticle()
    {
        $payload = [
            'title' => 'Title for the updated article',
            'body' => 'Body for the updated article.'
        ];

        $json = json_encode($payload, true);

        return $json;
    }

    /**
     * @return string
     */
    public function createInvalidJSONPayload()
    {
        $payload = [
            'title' =>  '',
            'body'  =>  ''
        ];

        $json = json_encode($payload, true);

        return $json;
    }
}