=== Candidate Application Form ===
Contributors: FlaxlandsConsulting
Donate link: http://responsecoordinator.com/
Tags: Candidate Application Form
Requires at least: 3.0
Tested up to: 3.9
Stable tag: /trunk/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add a candidate application form to a job vacancy post, which allows the candidate to apply for the vacancy.

== Description ==
Where an organisation would like to advertise job vacancies on their website, posting vacancy adverts to wp-posts is a convenient way to manage a list of vacancies on your wp website. <br><br> **Candidate Application Form** is a simple Plugin that will allow an Admin to easily add an application form to any WordPress post. The form will enable a potential candidate to apply for the advertised job vacancy. <br><br>By default the Plugin is configured to request the candidate enters information into a fixed set of compulsory fields: <br><br>*FirstName*<br>*LastName*<br>*Telephone*<br>*Mobile*<br>*Email Address*<br><br>*The candidate MUST also attach a CV document* (Word or PDF format only) <br><br>An additional (optional) field allows the candidate to enter *Additional Information* to support their job application. <br><br>*Flaxlands Consulting* originally built the Candidate Application Form Plugin to send candidate application details to the API of our applicant tracking software – Interview Coordinator http://interviewcoordinator.com . However as part of our development and testing procedure we created an alternative method which will simply send an email notification, instead of using HTTP requests to communicate with the API. The email contains all the candidate application information and an attached CV document. <br><br>We got some feedback that this option might be useful for people who do not yet have an applicant tracking database or if they do, but it does not have a suitable API. We therefore released the Email notification version of Candidate Application Form as a free Plugin under the GPF license. <br><br>The FULL version of the Candidate Application Form will allow the Admin to edit the fields displayed on the application form and upload up to 3 associated documents, instead of 1. The FULL version can also be configured to communicate with any appropriate remote API. Please refer to http://responsecoordinator.com  

== Installation ==
* From the WP admin panel, click "Plugins" -> "Add new".<br><br>
 *In the browser input box, type "Candidate Apply Form".<br><br>
*Select the "Candidate Apply Plugin" plugin (authored by "Flaxlands Consulting Ltd"), and click "Install".<br><br>
*Then “Activate” the plugin. <br><br>
*Place the shortcode "[apply-form]" on any page, post or widget to show the apply form. <br>

== Frequently Asked Questions ==

= Do you offer support on Candidate Application Form? =
The free version of Candidate Application Form is a fairly simply plugin and there isn’t really much that can go wrong. However, if you find something you think doesn’t work well please feel free to contact us using any of the contact details on our website [link] http://www.responsecoordinator.com<br>

= I’d like to insert candidate applications directly into my Applicant Tracking database (ATS). Is that possible with Candidate Application Form? =
Yes, it is possible, but will depend on various factors.  Part of our business is to help people build recruitment and careers websites from WordPress templates. We therefore built Candidate Application Form in such a way that it can be configured to link to any ATS which has a suitable API (Application Programmers Interface) <br><br>
However, at the moment we have only programmed interfaces for a limited number of ATS systems. Any additional configuration/programming necessary to link Candidate Application From to a new ATS may incur a charge.  Please contact us via our website (http://www.responsecoordinator.com) to enquire whether we have programmed to link to your ATS.

= Is it possible to edit the fields on the Candidate Application Form? =
Yes, but only with the FULL (paid) version of Candidate Application From. If your ambition is to create a more detailed candidate application form but you still only want to send an email notification, then you might best look at other free form creation plugins. There is not much point in paying us for a plugin feature that you can get elsewhere for free. <br><br>
The value added to the paid version of Candidate Application Form is primarily in its ability to send WordPress custom field data via http requests. <br><br>
This ability to send custom field data via http post/get is often important when linking job applications to job adverts created in an Applicant Tracking System database

== Screenshots ==
1.	This screen shot shows an example of what the Candidate Application Form might look like on Job Advert posted to a WordPress template site
http://img.we-record.com/WordPressPlugin/Candidate-Apply-Form-Screen-Example.png
<br><br>
2.	This is a screen shot of the Settings page for the Candidate Application Form plugin
http://img.we-record.com/WordPressPlugin/Candidate-Apply-Form-Settings-Screen-Example.png

== Changelog ==
= 1.0 =
* The initial version of Candidate Application Form

== Upgrade Notice ==
None