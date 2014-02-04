<?php 
\OCP\Util::addStyle('chat', 'bootstrap-stripped');
\OCP\Util::addScript('chat', 'vendor/angular/angular.min');
\OCP\Util::addScript('chat', 'vendor/angular/angular-sanitize');
\OCP\Util::addStyle('chat', 'main');
\OCP\Util::addScript('chat', 'classes');
\OCP\Util::addScript('chat', 'app/app');
\OCP\Util::addScript('chat', 'app/controllers/convcontroller');
\OCP\Util::addScript('chat', 'app/directives/popover');
\OCP\Util::addScript('chat', 'handlers');
?>
<div ng-controller="ConvController" ng-app="chat" id="app">
	<div id="loading-panel" class="icon icon-loading"  class="panel">
        &nbsp;
    </div>
	<div id="main-panel" class="panel">
		<ul id="app-navigation" >
			<li id="conv-list-new-conv">
				<div id="new-conv-text" ng-click="toggleNewConv()" ng-if="showNewConvText" >
				    <div class="icon icon-add">&nbsp;</div><span>New Conversation</spn>
			    </div>
				<div id="new-conv-form" ng-if="!showNewConvText">
				    <input id="new-conv-input" placeholder="Contact name" type="text" >
				    <button id="new-conv-button" class="primary">
				        Start
			    	</button>
                </div>
			</li>
			<li ng-click="makeActive(conv.id)" ng-repeat="conv in convs" class="conv-list-item" id="conv-list-{{ conv.id }}">
         		<span id="conv-new-msg-{{ conv.id }}" class="conv-new-msg">&nbsp;</span>
    			<div
    			    popover
    			    id="conv-leave-{{ conv.id }}"
    			    class="icon icon-close icon-32 right" 
    			    title="Are you sure you want to leave this conversation?" 
				    template-url="/apps/chat/js/app/templates/popover-button.html" 
				    conv-id="{{ conv.id }}"
				    on-submit="leave(arg1, conv.id)"
			    >
                    &nbsp;
		        </div>
				<div 
				    popover
    			    id="conv-invite-{{ conv.id }}"
				    class="icon icon-add right icon-32"
				    title="Invite user to add to conversation"
    			    template-url="/apps/chat/js/app/templates/popover-form.html" 
				    on-submit="invite(arg1, conv.id)"
    			    conv-id="{{ conv.id }}"
			    >
                    &nbsp;
                </div>
                <div ng-repeat="user in conv.users | filter:'!' + currentUser" class="icon-list icon-{{ user }}"></div>
                <div style="clear:both;"></div>
                <span ng-repeat="user in conv.users | filter:'!' + currentUser" class="icon-list" >
                    {{ user }}
                </span>
		    </li>
		</ul>
        <div id="app-content">
	        <div id="empty-window" class="panel">
                <div ng-repeat="contact in contacts" class="contact" style="background-image: url(/index.php/apps/contacts/addressbook/local/1/contact/{{ contact.id }}/photo);">
                    <label>
                        {{ contact.displayname }}
                    </label>
                </div>
    	    </div>
        	<div id="chat-window">
    			<section ng-click="focusMsgInput()" id="chat-window-body">
    				<div id="chat-window-msgs">
    					<div class="chat-msg-container" ng-repeat="msg in convs[activeConv].msgs">
    						<div ng-if="msg.time" class="chat-msg-time">
    							{{ msg.time.hours }} : {{ msg.time.minutes }}
    						</div>
    						<div class="chat-msg">
    							<div class="icon-{{ msg.user }}">
    							</div>
    							<p ng-bind-html="msg.msg">
                                    placeholder
    							</p>
    						</div>
    					</div>
    				</div>
    			</section>
    			<footer id="chat-window-footer">
    				<form ng-submit="sendChatMsg()"> 
    					<input ng-focus="" ng-blur="" ng-model="chatMsg" autocomplete="off" type="text" id="chat-msg-input" placeholder="Chat message">
    					<input id="chat-msg-send" type="submit"  value="Send" />
    				</form>
    			</footer>
    		</div>
	    </div>
	</div>
</div>