<?php

namespace ZfExtraTest\Mail;

use PHPUnit_Framework_TestCase;
use Zend\Mail\Address;
use Zend\Mail\AddressList;
use Zend\Mime\Mime;
use ZfExtra\Mail\Attachment\AttachmentInterface;
use ZfExtra\Mail\Attachment\StringAttachment;
use ZfExtra\Mail\Message;

/**
 *
 * @author Alex Oleshkevich <alex.oleshkevich@gmail.com>
 * @group zfe-mail
 */
class MessageTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \ZfExtra\Mail\Message::getFrom
     */
    public function testSetFrom()
    {
        $message = new Message;
        $message->setFrom('dev@example.com');
        
        $this->assertInstanceOf(Address::class, $message->getFrom());
        $this->assertEquals('dev@example.com', $message->getFrom()->getEmail());
        $this->assertNull($message->getFrom()->getName());
        
        $message->setFrom('dev@example.com', 'example');
        $this->assertEquals('dev@example.com', $message->getFrom()->getEmail());
        $this->assertEquals('example', $message->getFrom()->getName());
    }

    /**
     * @covers \ZfExtra\Mail\Message::__call
     * @covers \ZfExtra\Mail\Message::setAddresses
     * @covers \ZfExtra\Mail\Message::addAddresses
     * @covers \ZfExtra\Mail\Message::getAddresses
     */
    public function testAddTo()
    {
        $message = new Message;
        $message->setTo('dev@example.com');
        
        $this->assertInstanceOf(AddressList::class, $message->getTo());
        $this->assertCount(1, $message->getTo());
        
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
        
        $message = new Message;
        $message->addTo('dev@example.com', 'example');
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
        $this->assertEquals('example', $message->getTo()->current()->getName());
        
        $message = new Message;
        $message->addTo(array('dev@example.com', 'dev2@example.com'));
        $this->assertCount(2, $message->getTo());
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
        $this->assertEquals('dev2@example.com', $message->getTo()->next()->getEmail());
        
        $message = new Message;
        $message->addTo(array('dev@example.com' => 'example', 'dev2@example.com'));
        $this->assertCount(2, $message->getTo());
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
        $this->assertEquals('example', $message->getTo()->current()->getName());
        $this->assertEquals('dev2@example.com', $message->getTo()->next()->getEmail());
        
        $addresses = new AddressList;
        $addresses->add('dev@example.com');
        $message = new Message;
        $message->addTo($addresses);
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
        
        $address = new Address('dev@example.com');
        $message = new Message;
        $message->addTo($address);
        $this->assertEquals('dev@example.com', $message->getTo()->current()->getEmail());
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getSubject
     */
    public function testSetSubject()
    {
        $message = new Message;
        $message->setSubject('subject');
        $this->assertEquals('subject', $message->getSubject());
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getSender
     */
    public function testSetSender()
    {
        $message = new Message;
        $message->setSender('dev@example.com');
        
        $this->assertInstanceOf(Address::class, $message->getSender());
        $this->assertEquals('dev@example.com', $message->getSender()->getEmail());
        $this->assertNull($message->getSender()->getName());
        
        $message->setSender('dev@example.com', 'example');
        $this->assertEquals('dev@example.com', $message->getSender()->getEmail());
        $this->assertEquals('example', $message->getSender()->getName());
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getAttachments
     * @covers \ZfExtra\Mail\Message::addAttachment
     */
    public function testSetAttachments()
    {
        $message = new Message;
        $message->setAttachments([new StringAttachment([]), new StringAttachment([])]);
        $this->assertCount(2, $message->getAttachments());
        
        $message->addAttachment(new StringAttachment([]));
        $this->assertCount(3, $message->getAttachments());
        
        $this->assertInstanceOf(AttachmentInterface::class, $message->getAttachments()[0]);
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getBody
     * @covers \ZfExtra\Mail\Message::setBodyHtml
     * @covers \ZfExtra\Mail\Message::getBodyHtml
     * @covers \ZfExtra\Mail\Message::setBodyText
     * @covers \ZfExtra\Mail\Message::getBodyText
     */
    public function testSetBody()
    {
        $message = new Message;
        $message->setBody('html', Mime::TYPE_HTML);
        $this->assertEquals('html', $message->getBody(Mime::TYPE_HTML));
        
        $message->setBody('text', Mime::TYPE_TEXT);
        $this->assertEquals('text', $message->getBody(Mime::TYPE_TEXT));
        
        $message->setBodyHtml('body html');
        $this->assertEquals('body html', $message->getBodyHtml());
        
        $message->setBodyText('body text');
        $this->assertEquals('body text', $message->getBodyText());
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getCharset
     */
    public function testSetCharset()
    {
        $message = new Message;
        $message->setCharset('UTF-8');
        $this->assertEquals('UTF-8', $message->getCharset());
    }
    
    /**
     * @covers \ZfExtra\Mail\Message::getEncoding
     */
    public function testSetEncoding()
    {
        $message = new Message;
        $message->setEncoding(Mime::ENCODING_7BIT);
        $this->assertEquals(Mime::ENCODING_7BIT, $message->getEncoding());
    }
    
    public function testIsMultipart()
    {
        $message = new Message;
        $this->assertFalse($message->isMultipart());
        
        $message->addAttachment(new StringAttachment([]));
        $this->assertTrue($message->isMultipart());
        
        $message = new Message;
        $message->setBodyText('text');
        $this->assertFalse($message->isMultipart());
        $message->setBodyHtml('html');
        $this->assertTrue($message->isMultipart());
    }
}
