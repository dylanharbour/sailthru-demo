#### Sailthru on 5.3

This is an example of a basic implementation of Sailthru using Laravel notificatons. This is working, please see 
screenshots in PR. I've started with a clean install of Laravel 5.3 to illustrate how easy it is to get going. 
After generating the standard Laravel Auth scaffold I added 1 new notification class called "WelcomeEmailNotification"
. This gets triggered after user registration. In practice, you'd implement one class for each 
type of notification in the app (ProfileReminderNotification, NewsletterWelcomeNotificaiton etc etc). 

#### Getting Started
* Clone Repo
* Checkout the Sailthru branch (I kept the changes in branch to keep track of easier)
* Composer install, setup your ENV variables / DB / Mail Settings
* Insert your `$apiKey` and `$apiSecret` in the `SailthruChannel.php` (or pull from environment variable/config)
* Register a new user. 

The `WelcomeEmailNotification` will fire with two notifications. One via normal mail and one via the SailtruChannel


#### Benefits over current implementation

* Lightweight and Laravel friendly. Easy to understand and debug. 

* No need to swap out mail drivers: Sailthru notifications are not treated as emails so they don't interfere with our 
current mail drivers. That makes it a non breaking change to normal mails. 

* Define the Email and Sailthru Messages on the same class. This makes it easier to see what is happening. No need 
for configs to store various templates / overrides. 

* Run in Parallel. We can run in parallel, or do dynamic switching on the fly between Sailthru and Mail. Making it 
easier to "warm" the IP / Account. Switching between channels is as easy as modifying the `via()` method on the 
notification to either go by Mail, Sailthru or both. This can easily be done by a simple global `config('sailthru
.enabled')
` type of config, or a more specific one per domain / template / anything else. Switching to a different channel is 
implemented by design, 
so it's not complicated.  

* Easy to send Admin mails via mail and other mails via Sailthru.

* Email templates using the mail method are beautifully styled already and super easy to implement / customize / 
change. 

* 100% fallback. If sailthru goes down, we switch our config off and start sending by normal mail. This *should* be 
 the case with our current implementation too, but notifications would make it easier to maintain the two channels 
 together.
 

* Notifications are amazing to test with the Notifications fake. We can use the Notifications fake to test the flow 
of our our 
app, and only need to use Mailthief / Mailtrap / Sailthru Fake to test each notification at most, once. 
Possibly, Developers could
 do super fast testing while developing with no need to test the external service (Mailthief / Sailthru Test fake). 
 The last leg to the external service can be run on builds only and can possibly be run in parallel with the rest of 
 the tests to speed up feedback.  
 
 * Extend past Mail / Sailthru: We can define instant Slack notifications, Push Notifications to our Web Apps, SMS to 
 phones etc all super easily without having to re-write how / when a notification is triggered. Many major 
 notifications channels are already supported out of the box like Slack and Push Notification services. 
 
 
#### To Do
*  I did this in just under an hour, it obviously needs more refinement to implement the Client better by a facade / 
 service provider. But that's not too complex if we don't have to hack with our mail driver. 
* Guest users would need a GuestUser class that uses the `notifiable` trait. This class would need to implement the 
minim requirements for Sailthru (i.e. Store a Name, Email, etc). We could also make Advertisers Notifiable to send a 
notification to an Advertiser (i.e. Not to a user - following different rules. ).  
* I've ignored the rest of the Sailthru "features" like Mailing list management.
 * Notification `toSailthru()` currently returns an instance of itself. In practice, we'd probably have a sperate 
 `SailthruNotify` type of  Class which can encapsulate the templat name and variables better. 