<?php

namespace NotificationChannels\IonicPushNotifications\Test;

use DateTime;
use Illuminate\Support\Arr;
use NotificationChannels\IonicPushNotifications\IonicPushMessage;
use PHPUnit_Framework_TestCase;

class MessageTest extends PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\IonicPushNotifications\IonicPushMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();
        $this->message = new IonicPushMessage('my-security-profile');
    }

    /** @test */
    public function it_accepted_a_security_profile_when_constructing_a_message()
    {
        $this->assertEquals('my-security-profile', Arr::get($this->message->toArray(), 'profile'));
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = IonicPushMessage::create('my-security-profile');

        $this->assertEquals('my-security-profile', Arr::get($this->message->toArray(), 'profile'));
    }

    /** @test */
    public function by_default_it_will_use_tokens_as_user_identifier()
    {
        $this->assertEquals($this->message->getSendToType(), 'tokens');
    }

    /** @test */
    public function it_can_set_the_title()
    {
        $this->message->title('myTitle');

        $this->assertEquals('myTitle', Arr::get($this->message->toArray(), 'notification.title'));
    }

    /** @test */
    public function it_can_set_the_message()
    {
        $this->message->message('myMessage');

        $this->assertEquals('myMessage', Arr::get($this->message->toArray(), 'notification.message'));
    }

    /** @test */
    public function it_can_set_the_sound()
    {
        $this->message->sound('sound.wav');

        $this->assertEquals('sound.wav', Arr::get($this->message->toArray(), 'notification.sound'));
    }

    /** @test */
    public function it_can_set_a_schedule_from_string()
    {
        $date = new DateTime('tomorrow');

        $this->message->scheduled('tomorrow');

        $this->assertEquals($date->format(DateTime::RFC3339), Arr::get($this->message->toArray(), 'scheduled'));
    }

    /** @test */
    public function it_can_set_a_schedule_from_datetime()
    {
        $date = new DateTime('tomorrow');

        $this->message->scheduled($date);

        $this->assertEquals($date->format(DateTime::RFC3339), Arr::get($this->message->toArray(), 'scheduled'));
    }

    /** @test */
    public function it_can_set_the_payload()
    {
        $this->message->payload(['foo' => 'bar']);

        $this->assertEquals(['foo' => 'bar'], Arr::get($this->message->toArray(), 'notification.payload'));
    }

    /** @test */
    public function it_can_set_ios_specific_options()
    {
        $this->message->iosBadge(5);

        $this->assertEquals(5, Arr::get($this->message->toArray(), 'notification.ios.badge'));
    }

    /** @test */
    public function it_can_set_android_specific_options()
    {
        $this->message->androidIcon('ionitron.png');

        $this->assertEquals('ionitron.png', Arr::get($this->message->toArray(), 'notification.android.icon'));
    }

    /** @test */
    public function it_converts_camel_case_method_name_to_snake_case_array_key()
    {
        $this->message->iosContentAvailable(1);

        $this->assertTrue(Arr::has($this->message->toArray(), 'notification.ios.content_available'));
    }

    /** @test */
    public function it_wont_let_us_add_invalid_device_specific_options()
    {
        $this->message->iosFooBar(1);

        $this->assertFalse(Arr::has($this->message->toArray(), 'notification.ios.foo_bar'));
    }
}
