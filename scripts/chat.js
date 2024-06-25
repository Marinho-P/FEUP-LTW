document.addEventListener("DOMContentLoaded", function () {
  // Add event listeners for tab toggling
  var buyingTab = document.getElementById("buying-tab");
  var sellingTab = document.getElementById("selling-tab");

  if (buyingTab) {
    buyingTab.addEventListener("click", function (event) {
      highlightTab("buying-tab");
    });
  }

  if (sellingTab) {
    sellingTab.addEventListener("click", function (event) {
      highlightTab("selling-tab");
    });
  }
  const urlParams = new URLSearchParams(window.location.search);
  const tab = urlParams.get("tab");
  if (tab === "0" || tab === "1") {
    highlightTab(tab === "0" ? "buying-tab" : "selling-tab");
  } else {
    // Default to tab 0 if no tab is specified in the URL
    highlightTab("buying-tab");
  }

  function highlightTab(selectedTabId) {
    // Remove 'selected' class from both tabs
    var buyingTab = document.getElementById("buying-tab");
    var sellingTab = document.getElementById("selling-tab");

    if (buyingTab) {
      buyingTab.classList.remove("selected");
    }
    if (sellingTab) {
      sellingTab.classList.remove("selected");
    }

    // Add 'selected' class to the clicked tab
    var selectedTab = document.getElementById(selectedTabId);
    if (selectedTab) {
      selectedTab.classList.add("selected");
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Make AJAX request to fetch chat data

  var chatContainer = document.getElementById("chat-container");
  if (!chatContainer) return;
  var currentlySelectedTab = chatContainer.getAttribute("data-tab");
  var xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "../actions/actionGetChats.php?tab=" + currentlySelectedTab,
    true
  );
  xhr.onload = function () {
    if (xhr.status == 200) {
      var chats = JSON.parse(xhr.responseText);
      if (chats.length == 0) {
        var html = `<div id='buying-content' style='display: ${
          currentlySelectedTab == 0 ? "block" : "none"
        }'>
                    <img width='140' height='140' src='../images/assets/no_messages.png' alt='Error loading' >
                    <h5 data-testid='empty-list-title' data-cy='conversations-list-empty-list-title'>No open messages found</h5>
                    <p data-testid='empty-list-subtitle'>You haven't started any conversations yet. When you send a message to a salesperson, the chat will appear here.</p>
                </div>
                <div id='selling-content' style='display: ${
                  currentlySelectedTab == 1 ? "block" : "none"
                }'>
                    <img width='140' height='140' src='../images/assets/no_messages.png' alt='Error loading' >
                    <h5 data-testid='empty-list-title' data-cy='conversations-list-empty-list-title'>No open messages found</h5>
                    <p data-testid='empty-list-subtitle'>You received any messages yet. When you someone sends you a message, the chat will appear here.</p>
                </div>`;

        document.getElementById("chat-user-list").innerHTML = html;
      } else {
        var chatContainer = document.getElementById("chat-user-list");
        chats.forEach(function (chat) {
          // Generate HTML for each chat and append it to the chat container
          var userId;
          if (currentlySelectedTab == 0) {
            userId = chat.sellerId;
          } else {
            userId = chat.buyerId;
          }
          getMostRecentMessage(chat.chatId, (message) => {
            getUser(userId, (user) => {
              var timeSentDate = new Date(message.lastMessage.timeSent);
              var currentTime = new Date();

              var difference = currentTime - timeSentDate;

              var differenceInSeconds = Math.floor(difference / 1000);
              var differenceInMinutes = Math.floor(differenceInSeconds / 60);
              var differenceInHours = Math.floor(differenceInMinutes / 60);
              var differenceInDays = Math.floor(differenceInHours / 24);

              var timeSentString;
              if (differenceInDays > 0) {
                timeSentString = differenceInDays + " days ago";
              } else if (differenceInHours > 0) {
                timeSentString = differenceInHours + " hours ago";
              } else if (differenceInMinutes > 0) {
                timeSentString = differenceInMinutes + " minutes ago";
              } else {
                timeSentString = differenceInSeconds + " seconds ago";
              }
              let chatContent =
                message.lastMessage.senderId == userId
                  ? message.lastMessage.content
                  : "You: " + message.lastMessage.content;
              var chatHtml =
                "<div class='user-chat' id='user-chat-" +
                userId +
                "'>" +
                "<img src='../images/user" +
                userId +
                ".png' alt='User Image' class='user-chat-image'>" +
                "<div class='user-info'>" +
                "<h2>" +
                user.user.name +
                "</h2>" +
                "<h3>" +
                "@" +
                user.user.username +
                "</h3>" +
                "<p class='last-message'>" +
                chatContent +
                "</p>" +
                "<p class='time-sent'>" +
                timeSentString +
                "</p>" +
                "</div>" +
                "</div>";
              var tempDiv = document.createElement("div");
              tempDiv.innerHTML = chatHtml;
              var chatElement = tempDiv.firstChild;
              chatElement.addEventListener("click", function () {
                openChatBox(user.user, chat);
              });

              // Add the HTML element to the chatContainer
              chatContainer.appendChild(chatElement);
            });
          });
        });
      }
    } else {
      console.error("Error fetching chat data");
    }
  };
  xhr.send();

  function openChatBox(user, chat) {
    //TODO
    const currentUrl = window.location.href;

    // Create a URL object using the current URL
    const urlObj = new URL(currentUrl);

    // Get the search parameters
    const params = new URLSearchParams(urlObj.search);

    // Extract the 'tab' parameter
    const tab = params.get("tab");
    let messageContainer = document.querySelector(".chat-box");
    messageContainer.innerHTML = "";
    let dealButtonHtml = "";
    let modalHtml = "";
    if (tab === "1") {
      dealButtonHtml = `<span id='dealButton' class='input-group-text send-icon'><i class='fas fa-handshake' style='padding: 10px;'></i> </span>`;
      modalHtml = `<div id="itemModal" class="modal">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Items Available To Offer</h2>
          <ul id="itemList">
              <!-- Items will be added here dynamically -->
          </ul>
          <div id="noItemsMessage" style="display: none;">
        <p>You are not currently selling any items.</p>
    </div>

          <button id="submitDealButton" class="submit-deal-btn" disabled>Submit Deal</button>
      </div>
  </div>`;
    }
    var messagesHtml =
      `
        <!-- msg-header section starts -->
        <div class="msg-header">
        <img src="../images/user` +
      user.id +
      `.png" class="msgimg" />
          <div class="chat-about">
            <div class="chat-with">Chat with ` +
      user.name +
      `</div>
            <div class="online">Online</div>
          </div>
        </div>
        <!-- msg-header section ends -->
        
        <!-- Chat inbox  -->
        <div class="chat-page">
              <!-- Message container -->
              <div class="msg-page">

                <!-- Incoming messages -->

                <!-- Outgoing messages -->
                </div>
        
            <!-- msg-bottom section -->
        
            <div class="msg-bottom">
            ${dealButtonHtml}
            ${modalHtml}
              <div class="input-group">
                <input id="messageInput" type="text" class="form-control" placeholder="Write message..."/>
                </div>
                <span id="sendButton" class="input-group-text send-icon">
                <i class="material-icons">send</i>
                </span>
            </div>
          </div>
        </div>
        `;
    var chatBox = document.querySelector(".chat-box");
    chatBox.innerHTML = messagesHtml;
    sendButton = document.getElementById("sendButton");
    messageInput = document.getElementById("messageInput");

    if (tab === "1") {
      xhr = new XMLHttpRequest();
      xhr.open("POST", "../actions/actionGetUserItems.php", true);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            const userItems = response;

            const noItemsMessage = document.getElementById("noItemsMessage");
            if (userItems.length === 0) {
              noItemsMessage.style.display = "block";
            } else {
              noItemsMessage.style.display = "none";
            }

            const modal = document.getElementById("itemModal");
            const span = document.getElementsByClassName("close")[0];
            dealButton = document.getElementById("dealButton");

            dealButton.addEventListener("click", function () {
              const itemList = document.getElementById("itemList");
              itemList.innerHTML = ""; // Clear previous items
              userItems.forEach((item) => {
                const listItem = document.createElement("li");
                const checkbox = document.createElement("input");

                checkbox.type = "checkbox";
                checkbox.name = "item";

                let priceInput;
                if (item.stock === 0) {
                  priceInput = document.createElement("p");
                  outofstock.innerHTML = "Out of stock";
                } else {
                  priceInput = document.createElement("input");
                  priceInput.type = "number";
                  priceInput.placeholder = "Enter quantity ...";
                  priceInput.classList.add("price-input");
                  priceInput.setAttribute("disabled", true); // Disable the price input field by default
                  priceInput.setAttribute("min", 1);
                  priceInput.setAttribute("max", item.Stock);

                  // Event listener for checkbox change
                  checkbox.addEventListener("change", function () {
                    if (checkbox.checked) {
                      priceInput.removeAttribute("disabled");
                    } else {
                      priceInput.setAttribute("disabled", true);
                    }
                    updateSubmitButtonState();
                  });

                  priceInput.addEventListener("input", function () {
                    if (!isValidQuantity(priceInput)) {
                      priceInput.value = priceInput.value.slice(0, -1);
                    }
                    updateSubmitButtonState(); // Update the state of the Submit Deal button
                  });
                }

                listItem.appendChild(checkbox);
                listItem.appendChild(
                  document.createTextNode(
                    item.Name + " - Current price: " + item.Price + " €"
                  )
                );
                listItem.appendChild(priceInput);
                itemList.appendChild(listItem);
              });

              const discountDiv = document.createElement("div");
              discountDiv.classList.add("discount-box");

              const discountInput = document.createElement("input");
              discountInput.type = "text";
              discountInput.placeholder = "Enter discount percentage ...";
              discountInput.classList.add("discount-input");

              discountDiv.appendChild(discountInput);
              itemList.appendChild(discountDiv);

              discountInput.addEventListener("input", function () {
                updateSubmitButtonState();
              });

              modal.style.display = "block";
              const submitButton = document.getElementById("submitDealButton");
              submitButton.addEventListener("click", () => {
                //fazer as cenas na base de dados
                const checkboxes = document.querySelectorAll(
                  'input[type="checkbox"]'
                );

                const priceInputs = document.querySelectorAll(".price-input");
                const items = {};

                checkboxes.forEach((checkbox, index) => {
                  if (checkbox.checked) {
                    items[userItems[index].ItemId] = {
                      quantity: priceInputs[index].value,
                    };
                  }
                });
                  xhr = new XMLHttpRequest();
                  xhr.open("POST", "../actions/actionSubmitDeal.php", true);
                  xhr.setRequestHeader("Content-Type", "application/json");
                  xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                      if (xhr.status === 200) {
                        response = JSON.parse(xhr.responseText);
                        sendMessageFromInput("Here is your coupon use it when checking out "+ response.coupon.code, chat, user);
                      } else {
                        console.error(
                          "Error submitting deal. Status code: " + xhr.status
                        );
                        console.error(xhr.responseText);
                      }
                    }
                  };
                  xhr.onerror = function () {
                    console.error("Network error occurred.");
                  };
                  xhr.send(
                    JSON.stringify({
                      buyerId: chat.buyerId,
                      items: items,
                      discount: discountInput.value,
                    })
                  );

                modal.style.display = "none";
              });
            });
            span.onclick = function () {
              modal.style.display = "none";
              submitButton = document.getElementById("submitDealButton");
              submitButton.disabled = true;
            };

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
              if (event.target == modal) {
                modal.style.display = "none";
                submitButton = document.getElementById("submitDealButton");
                submitButton.disabled = true;
              }
            };
          } else {
            console.error("Error fetching data. Status code: " + xhr.status);
            console.error(xhr.responseText);
          }
        }
      };
      xhr.onerror = function () {
        console.error("Network error occurred.");
      };
      xhr.send();
    }

    sendButton.addEventListener("click", function (event) {
      // Check if the clicked element is the send button
      event.preventDefault();
      sendMessageFromInput(messageInput.value.trim(), chat, user);
    });

    messageInput.addEventListener("keyup", function (event) {
      // Check if Enter key was pressed (keyCode 13)
      if (event.key === "Enter") {
        sendMessageFromInput(messageInput.value.trim(), chat, user);
      }
    });
    getAndUpdateMessages(chat, user);
  }

  function updateSubmitButtonState() {
    const submitButton = document.getElementById("submitDealButton");
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const priceInputs = document.querySelectorAll(".price-input");
    const discountInput = document.querySelector(".discount-input");

    let enableButton = false;

    checkboxes.forEach((checkbox, index) => {
      if (checkbox.checked) {
        if (
          priceInputs[index].value !== "" &&
          isValidDiscount(discountInput.value) &&
          discountInput.value !== "" &&
          isValidQuantity(priceInputs[index])
        ) {
          enableButton = true;
        }
      }
    });

    submitButton.disabled = !enableButton;
  }

  function isValidQuantity(input) {
    const value = parseFloat(input.value);
    const min = parseFloat(input.min);
    const max = parseFloat(input.max);
    return value >= min && value <= max;
  }
  function isValidDiscount(value) {
    return /^(?:100(?:\.0+)?|\d{0,2}(?:\.\d+)?(?:|))$/.test(value);
  }

  function getAndUpdateMessages(chat, user) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../actions/actionGetMessages.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let response = JSON.parse(xhr.responseText);
          drawUI(response, user, chat);
          document
            .querySelector(".msg-page")
            .scrollTo(0, document.querySelector(".msg-page").scrollHeight);
        } else {
          console.error("Error fetching data. Status code: " + xhr.status);
          console.error(xhr.responseText);
        }
      }
    };
    xhr.onerror = function () {
      console.error("Network error occurred.");
    };
    xhr.send(JSON.stringify({ chatId: chat.chatId }));
  }

  function drawUI(data, user, chat) {
    let msgPage = document.querySelector(".msg-page");
    let messages = "";
    const monthNames = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    let myId = user.id == chat.buyerId ? chat.sellerId : chat.buyerId;
    let sortedMessages = data.messages.sort((a, b) => {
      return new Date(a.timeSent) - new Date(b.timeSent);
    });
    sortedMessages.forEach((message) => {
      const timestamp = new Date(message.timeSent);
      const day = timestamp.getDate();
      const month = monthNames[timestamp.getMonth()];
      const hours = timestamp.getHours();
      const minutes = timestamp.getMinutes();
      if (message.senderId === myId) {
        messages += `
                  <div class="outgoing-chats">
                      <img src="../images/user${message.senderId}.png" class="outgoing-msgimg"/>
                    <div class="outgoing-msg">
                        <p>${message.content}</p>
                        <p class="chat-msg-time" style="text-align: right;">${hours}:${minutes} | ${month} ${day}</p>
                    </div>
                  </div>
                  `;
      } else {
        messages += `
                  <div class="received-chats">
                      <img src="../images/user${message.senderId}.png" class="msgimg"/>
                    <div class="received-msg">
                        <p>${message.content}</p>
                        <p class="chat-msg-time">${hours}:${minutes} | ${month} ${day}</p>
                    </div>
                  </div>
                  `;
      }
    });

    msgPage.innerHTML = messages;
  }

  const sendMessage = (message, chatId) => {
    return new Promise((resolve, reject) => {
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "../actions/actionSendMessage.php", true);
      xhr.setRequestHeader("Content-Type", "application/json");

      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            resolve(); // Resolve the promise on success
          } else {
            console.error("Error sending message. Status code: " + xhr.status);
            console.error(xhr.responseText);
            reject("Error sending message");
          }
        }
      };
      xhr.onerror = function () {
        console.error("Network error occurred.");
        reject("Network error occurred");
      };
      xhr.send(JSON.stringify({ chatId: chatId, content: message }));
    });
  };

  const sendMessageFromInput = async (message, chat, user) => {
    // Trim the input to remove leading and trailing whitespace
    // Check if the trimmed message is not empty
    if (message !== "") {
      // If the message is not empty, handle sending it
      await sendMessage(message, chat.chatId);

      const originalChatElement = document.querySelector(
        "#user-chat-" + user.id
      );
      if (!originalChatElement) return;

      const clonedChatElement = originalChatElement.cloneNode(true);

      const timeSent = clonedChatElement.querySelector(".time-sent");
      if (timeSent) timeSent.textContent = "Just now";

      const lastMessage = clonedChatElement.querySelector(".last-message");
      if (lastMessage) lastMessage.textContent = "You: " + message;

      messageInput.value = "";

      const chatContainer = document.getElementById("chat-user-list");
      if (chatContainer) {
        originalChatElement.remove();
        chatContainer.insertBefore(clonedChatElement, chatContainer.firstChild);
        chatContainer.scrollTop = 0;
      }

      getAndUpdateMessages(chat, user);
    }
  };

  const getUserId = (callback) => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../actions/actionGetUserId.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let response = JSON.parse(xhr.responseText);
          callback(response);
        } else {
          console.error("Error fetching data. Status code: " + xhr.status);
          console.error(xhr.responseText);
        }
      }
    };
    xhr.onerror = function () {
      console.error("Network error occurred.");
    };
    xhr.send();
  };

  const getMostRecentMessage = (chatId, callback) => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../actions/actionGetMostRecentMessage.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let response = JSON.parse(xhr.responseText);
          callback(response);
        } else {
          console.error("Error fetching data. Status code: " + xhr.status);
          console.error(xhr.responseText);
        }
      }
    };
    xhr.onerror = function () {
      console.error("Network error occurred.");
    };
    xhr.send(JSON.stringify({ chatId: chatId }));
  };

  const getUser = (userId, callback) => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../actions/actionGetUser.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          let response = JSON.parse(xhr.responseText);
          callback(response);
        } else {
          console.error("Error fetching data. Status code: " + xhr.status);
          console.error(xhr.responseText);
        }
      }
    };
    xhr.onerror = function () {
      console.error("Network error occurred.");
    };
    xhr.send(JSON.stringify({ userId: userId }));
  };

  function checkForNewMessages() {
    let xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      "../actions/actionGetChats.php?tab=" + currentlySelectedTab,
      true
    );
    xhr.onload = function () {
      if (xhr.status == 200) {
        let data = JSON.parse(xhr.responseText);
        if (data.chats.length === 0 || data.users.length === 0) {
          // If either array is empty, do nothing
          return;
        }
        for (let i = 0; i < data.chats.length && i < data.users.length; i++) {
          let chat = data.chats[i];
          let user = data.users[i];

          // Update messages for the current chat with user information
          getAndUpdateMessages(chat, user);
        }
      }
    };
  }
  setInterval(checkForNewMessages, 1000);
});
