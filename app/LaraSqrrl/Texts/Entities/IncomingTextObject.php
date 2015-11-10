<?php namespace App\LaraSqrrl\Texts\Entities;

class IncomingTextObject {

    private $messageSid;
    private $from;
    private $body;
    private $numMedia;
    /**
     * @var array
     */
    private $mediaUrl;
    /**
     * @var array
     */
    private $mediaType;

    /**
     * @param $messageSid
     * @param $from
     * @param $body
     * @param $numMedia
     * @param array $mediaUrl
     * @param array $mediaType
     */
    public function __construct($messageSid, $from, $body, $numMedia, $mediaUrl = [], $mediaType = [])
    {
        $this->messageSid = $messageSid;
        $this->from = $from;
        $this->body = $body;
        $this->numMedia = $numMedia;
        $this->mediaUrl = $mediaUrl;
        $this->mediaType = $mediaType;
    }

    /**
     * @return mixed
     */
    public function getMessageSid()
    {
        return $this->messageSid;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return bool
     */
    public function hasMedia()
    {
        return $this->numMedia > 0;
    }

    /**
     * @return mixed
     */
    public function getNumMedia()
    {
        return $this->numMedia;
    }

    /**
     * @param int $index
     * @return array|bool
     */
    public function getMediaUrl($index)
    {
        if (!empty($this->mediaUrl[$index]))
        {
            return $this->mediaUrl[$index];
        }

        return FALSE;
    }

    /**
     * @param int $index
     * @return array|bool
     */
    public function getMediaType($index)
    {
        if (!empty($this->mediaType[$index]))
        {
            return $this->mediaType[$index];
        }

        return FALSE;
    }

}
