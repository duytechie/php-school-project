<?php $this->layout("layouts/default", ["title" => "Page Not Found - CT275 Store"]) ?>

<?php $this->start("page") ?>
<div class="auth-container">
    <div class="auth-card" style="text-align: center;">
        <h2 style="font-size: 72px; margin-bottom: 10px; color: #717fe0;">404</h2>
        <h3 style="margin-bottom: 20px; color: #333;">Page Not Found</h3>
        <p style="color: #666; margin-bottom: 30px;">The page you are looking for doesn't exist or has been moved.</p>
        <a href="/" class="btn btn-auth" style="display: inline-block; width: auto; padding: 0 40px;">
            Back to Home
        </a>
    </div>
</div>
<?php $this->stop() ?>