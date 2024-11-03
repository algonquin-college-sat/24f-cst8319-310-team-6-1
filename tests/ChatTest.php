<?php

use PHPUnit\Framework\TestCase;

class ChatTest extends TestCase
{
    protected function setUp(): void
    {
        // Mock the db_connect function to avoid a real database connection
        if (!function_exists('db_connect')) {
            function db_connect() {
                return new class {
                    public function query($query) {
                        // Simulate different responses for INSERT and SELECT statements
                        if (strpos($query, "INSERT INTO `newchat`") !== false) {
                            return true; // Simulate successful insertion
                        }
                        if (strpos($query, "SELECT * FROM `newchat`") !== false) {
                            // Simulate result set for SELECT queries
                            return new class {
                                private $data = [
                                    [
                                        'id' => 1,
                                        'message' => 'Test message',
                                        'from' => 'testuser',
                                        'toUser' => 'Gig Worker'
                                    ]
                                ];
                                public function fetch_assoc() {
                                    return array_shift($this->data);
                                }
                            };
                        }
                        return false;
                    }

                    public function close() {
                        // Mock close method
                    }
                };
            }
        }
    }

    public function testSendMessage()
    {
        // Set POST variables as if a message is being sent
        $_POST['message'] = 'Test message';
        $_POST['from'] = 'testuser';
        $_POST['toUser'] = 'Gig Worker';

        // Capture the output
        ob_start();
        include __DIR__ . '/../newchat/TESTE/newchat/newchat.php';  // Adjusted path here
        $output = ob_get_clean();

        // Decode the JSON response
        $response = json_decode($output, true);

        // Assert that the message send status is as expected
        $this->assertArrayHasKey('send_status', $response, "Send status key is missing in the response.");
        $this->assertEquals("Message sent successfully", $response['send_status'], "Message send status does not match expected value.");
    }

    protected function tearDown(): void
    {
        // Reset POST data after each test to avoid interference between tests
        $_POST = [];
    }
}
