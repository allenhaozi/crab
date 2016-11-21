<?php
/**
 * log build
 *
 * @allenhaozi@gmail.com
 */
class Log_Build
{
    public static function test($strContent)
    {
        Crab_Log::Log('test','debug',$strContent);
    }
}
