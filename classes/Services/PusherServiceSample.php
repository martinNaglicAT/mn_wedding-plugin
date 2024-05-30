<?php
namespace MNWeddingPlugin\Services;

use Pusher\Pusher;

if (!defined('ABSPATH')) {
    exit;
}

class PusherService {
    protected $pusher;

    public function __construct() {
        $this->pusher = new Pusher(
            "[credentials]",
            "[credentials]",
            "[credentials]",
            ['cluster' => 'eu']
        );
    }

    public function getPusher() {
        return $this->pusher;
    }
}
