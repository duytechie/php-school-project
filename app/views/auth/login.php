<?php $this->layout("layouts/default", ["title" => "Login - CT275 Store"]) ?>

<?php $this->start("page") ?>
<div class="auth-container">
    <div class="back-home">
        <a href="/">
            <i class="fa fa-arrow-left"></i>
            Back to Store
        </a>
    </div>

    <div class="auth-card">
        <h2>Welcome Back</h2>

        <?php if (isset($messages['success'])): ?>
            <div class="alert alert-success">
                <?= $this->e($messages['success']) ?>
            </div>
        <?php endif ?>

        <form method="POST" action="/login">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email or Username</label>
                <input id="email" type="text" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                    name="email" value="<?= isset($old['email']) ? $this->e($old['email']) : '' ?>"
                    placeholder="Enter your email or username" required autofocus>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['email']) ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password"
                    class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" name="password"
                    placeholder="Enter your password" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback" style="display: block;">
                        <?= $this->e($errors['password']) ?>
                    </div>
                <?php endif ?>
            </div>

            <button type="submit" class="btn btn-auth">
                Sign In
            </button>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="/register">Create one</a></p>
        </div>
    </div>
</div>
<?php $this->stop() ?>