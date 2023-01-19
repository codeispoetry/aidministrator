document.addEventListener("DOMContentLoaded", function() {
   document
   .querySelector('#aidministrator-message')
   .addEventListener('keydown', function (e) {
      e = e || window.event;

      let command_number = localStorage.getItem('aidministrator-command-number');
      let commands = JSON.parse(localStorage.getItem('aidministrator-commands'));

      switch(e.keyCode) {
         case 38: // up
            command_number++;
         break;
         case 40: // down
            command_number--;
            if( command_number < 0 ){
               command_number = commands.length - 1;
            }
         break;
         default:
            return;
      }

      e.preventDefault();
      command_number %= commands.length
      localStorage.setItem('aidministrator-command-number', command_number);

      e.target.value = commands[command_number];
   });

   document
   .querySelector('#aidministrator')
   .addEventListener('submit', function (event) {
      event.preventDefault();

      const message_element = document.querySelector('#aidministrator-message');
      const waiting_element = document.querySelector('.aidministrator .waiting');

      if (message_element.value === '') {
         return;
      }

      const formData = new FormData(
         document.querySelector('#aidministrator')
      );

      waiting_element.style.display = 'block';
      add_message( 'human',message_element.value );
      remember_message( message_element.value );
      message_element.value  = '';
      

      // eslint-disable-next-line no-undef
      fetch(aidministrator.ajax_url, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
         },
         body:
            'action=aidministrator&nonce=' +
            aidministrator.nonce + // eslint-disable-line no-undef
            '&message=' +
            formData.get('aidministrator-message'),
      })
         .then((response) => {
            if (!response.ok) {
               throw new Error(aidministrator.messages.error); // eslint-disable-line no-undef
            }

            waiting_element.style.display = 'none';
            return response.json();
         })
         .then((text) => {
            if (text.error) {
               throw new Error(text.error); // eslint-disable-line no-undef
            }
            add_message( 'bot', text);
         })
         .catch((error) => {
            alert(error); // eslint-disable-line no-alert
         });
   });

   function add_message( who, message ){
      const element = document.createElement('div');
      element.classList.add('message');
      element.classList.add(who);
      element.innerHTML = message;

      let messages = document.querySelector('.aidministrator .messages');
      messages.appendChild(element);

      let chatbox = document.querySelector('.aidministrator .messages-container');
		chatbox.scrollTop = chatbox.scrollHeight;
   }

   function remember_message( message ){
      let commands = JSON.parse(localStorage.getItem('aidministrator-commands'));
      commands.unshift(message);
      localStorage.setItem('aidministrator-commands', JSON.stringify(commands));
      localStorage.setItem('aidministrator-command-number', -1);
   }

   document.querySelector('.aidministrator-expand').addEventListener('click', function (event) {
      event.preventDefault();
      document.querySelector('.aidministrator').classList.toggle('expanded');
   });

   let commands = ['zeige alle themes','welche benutzer gibt es','welche plugins sind installiert?'];
   //localStorage.setItem('aidministrator-commands', JSON.stringify(commands));
   localStorage.setItem('aidministrator-command-number', -1);

});

