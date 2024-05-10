<?php
namespace Weoneteam\PluginManager\Events;

use Illuminate\Queue\SerializesModels;

class ActivatedPluginEvent
{
    use SerializesModels;

    public function __construct(public string $plugin)
    {
    }
}
