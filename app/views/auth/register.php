<?php $this->layout("layouts/default", ["title" => "Create Account - CT275 Store"]) ?>

<?php $this->start("page") ?>
<div class="auth-container">
    <div class="back-home">
        <a href="/">
            <i class="fa fa-arrow-left"></i>
            Back to Store
        </a>
    </div>

    <div class="auth-card">
        <h2>Create Account</h2>

        <form method="POST" action="/register">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text"
                    class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" name="username"
                    value="<?= isset($old['username']) ? $this->e($old['username']) : '' ?>"
                    placeholder="Choose a username" required autofocus>
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['username']) ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    name="email" value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>"
                    placeholder="Enter your email" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['email']) ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="fullname">Full Name (Optional)</label>
                <input id="fullname" type="text" class="form-control" name="fullname"
                    value="<?= isset($old['fullname']) ? $this->e($old['fullname']) : '' ?>"
                    placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password"
                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" name="password"
                    placeholder="Create a password (min. 6 characters)" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['password']) ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password"
                    class="form-control <?= isset($errors['password_confirmation']) ? 'is-invalid' : '' ?>"
                    name="password_confirmation" placeholder="Confirm your password" required>
                <?php if (isset($errors['password_confirmation'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['password_confirmation']) ?>
                    </div>
                <?php endif ?>
            </div>

            <button type="submit" class="btn btn-auth">
                Create Account
            </button>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="/login">Sign in</a></p>
        </div>
    </div>
</div>
<?php $this->stop() ?>