<div class="modal fade" id="loginSignupModal" tabindex="-1" aria-labelledby="loginSignupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginSignupModalLabel">Login / Signup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="loginSignupTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup" type="button" role="tab" aria-controls="signup" aria-selected="false">Signup</button>
                    </li>
                </ul>
                <div class="tab-content" id="loginSignupTabContent">
                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <form method="POST" action="../src/auth_processor.php">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="login">Login</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="signup" role="tabpanel" aria-labelledby="signup-tab">
                        <form method="POST" action="../src/auth_processor.php">
                            <div class="mb-3">
                                <label for="signupFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="signupFirstName" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="signupLastName" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="signupEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="signupPassword" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="signupPhone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="signupAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="signupAddress" name="address" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success" name="signup">Signup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>