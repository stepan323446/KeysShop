<?php the_header(
    'Contact Us', 
    'Get in touch with us for more information, support, or collaboration. Find our contact details and send a message directly from the website.', 
    'contacts', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <div class="contacts__inner">
        <div class="contact-info article-text">
            <h1 class="p-title">Contact Us</h1>
            <p>Thank you for visiting KeysShop! If you have any questions or would like to get in touch with the developer, please feel free to reach out using the contact information below.</p>
            <h3>How to contact us:</h3>
            <ul>
                <li>E-mail: <a href="mailto:stevedekart2020@gmail.com">stevedekart2020@gmail.com</a></li>
                <li>LinkedIn: <a target="_blank" href="https://www.linkedin.com/in/stepan-turitsin-009354243/">Stepan Turitsin</a></li>
                <li>Website: <a target="_blank" href="https://steve-dekart.xyz/">steve-dekart.xyz</a></li>
            </ul>
            <p>I'll be happy to assist you and provide any additional information about this project.</p>
            <p>Looking forward to hearing from you!</p>
        </div>
        <div class="contact-form">            
            <?php
            if(isset($context['error_message']))
                the_alert($context['error_message'], 'warning', 'form-alert');

            if(isset($context['success_message']))
                the_alert($context['success_message'], 'success', 'form-alert');
            ?>
            <form class="form" method="post">
                <label for="contact-name">Name <span>*</span></label>
                <div class="input">
                    <input id="contact-name" name="name" type="name" maxlength="50" required>
                </div>

                <label for="contact-email">E-Mail <span>*</span></label>
                <div class="input">
                    <input id="contact-email" name="email" type="email" maxlength="50" required>
                </div>

                <label for="contact-content">Message <span>*</span></label>
                <div class="input">
                    <textarea id="contact-content" name="content" rows="7"></textarea required>
                </div>
                <?php
                if(isset($context['error_form']))
                    $context['error_form']->display_error();
                ?>
                <button id="contact-send" class="btn btn-primary" type="submit">Send</button>
            </form>
        </div>
    </div>
    
</div>

<?php the_footer() ?>