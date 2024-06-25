<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/userReview.class.php');
require_once(__DIR__ . '/../database/brand.class.php');
require_once(__DIR__ . '/../database/category.class.php');
require_once(__DIR__ . '/../database/size.class.php');
require_once(__DIR__ . '/../database/condition.class.php');
require_once(__DIR__ . '/../database/items.class.php');
require_once(__DIR__ . '/../database/chat.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

function drawChat($userId)
{
    $DB = getDatabaseConnection();
    $newuser = User::getUserWithId($DB, $userId);
    $currentlySelected = isset($_GET['tab']) ? intval($_GET['tab']) : 0; // Fetch currently selected value from URL parameters

    if ($newuser) {
        echo "<div id='chat-container' data-tab='$currentlySelected'>
        <section class='chat_user'>
            <div data-testid='tab-selector' class='tab-options' >
                <a href='?tab=0' id='buying-tab'> 
                    <div title='Buying'> 
                        <button type='button'>Buying</button>
                    </div>
                </a>
                <a href='?tab=1' id='selling-tab'> 
                    <div title='Selling'> 
                        <button type='button'>Selling</button>
                    </div>
                </a>
            </div>
            <div id='chat-user-list'>
            </div>
        </section>
        <section class='chat-box'>
            <div class='select-message-box'>
                <div style='position: relative;'>
                    <img style='width: 300px;' src='../images/assets/no_messages.png' alt='Error loading' >
                    <p class='select-conversation-title'>Select a conversation to start messaging</p>
                </div>
            </div>
            </section>
        </section>
        </div>";
    } else {
        echo "<p>Sorry, user not found.</p>";
    }
}



?>