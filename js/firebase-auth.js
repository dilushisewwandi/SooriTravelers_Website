/**
 * Firebase Authentication - Soori Travels
 * Uses Firebase Web SDK v10 (compat mode)
 * 
 * IMPORTANT: Replace the firebaseConfig values with your actual Firebase project credentials.
 * Go to Firebase Console → Project Settings → Web App → Config
 */

// Firebase configuration - REPLACE WITH YOUR ACTUAL CONFIG
  const firebaseConfig = {
    apiKey: "AIzaSyCyit2QjEPJ2dfn9V_q7aMPVIU7TG-6EDk",
    authDomain: "soori-travels.firebaseapp.com",
    projectId: "soori-travels",
    storageBucket: "soori-travels.firebasestorage.app",
    messagingSenderId: "122309750120",
    appId: "1:122309750120:web:8df8776155cd2a20527add"
  };

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();

/**
 * Auth state listener - runs on every page
 * Updates navbar button based on login state
 */
auth.onAuthStateChanged(function(user) {
    const authNavBtn = document.getElementById('auth-nav-btn');
    if (!authNavBtn) return;

    if (user) {
        // User is signed in
        authNavBtn.innerHTML = `
            <a data-aos="zoom-in-left" data-aos-delay="1300" href="profile_new.php" class="btn" id="profileBtn">User Profile</a>
        `;
    } else {
        // User is signed out
        const basePath = authNavBtn.getAttribute('data-basepath') || '';
        authNavBtn.innerHTML = `
            <a data-aos="zoom-in-left" data-aos-delay="1300" href="${basePath}login_new.php" class="btn" id="loginBtn">login</a>
        `;
    }
});

/**
 * Register with Email & Password
 */
async function registerWithEmail(name, email, password, phone, country) {
    try {
        const userCredential = await auth.createUserWithEmailAndPassword(email, password);
        const user = userCredential.user;

        // Update display name in Firebase
        await user.updateProfile({ displayName: name });

        // Sync user to MySQL database (separate try-catch so Firebase success is preserved)
        try {
            const response = await fetch('api/register_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    firebase_uid: user.uid,
                    name: name,
                    email: email,
                    phone: phone || null,
                    country: country || null
                })
            });

            if (!response.ok) {
                console.error('MySQL sync failed - HTTP', response.status);
            } else {
                const result = await response.json();
                if (!result.success) {
                    console.error('MySQL sync error:', result.message);
                }
            }
        } catch (syncError) {
            console.error('MySQL sync network error:', syncError.message);
        }

        return { success: true, user: user };
    } catch (error) {
        return { success: false, message: error.message };
    }
}

/**
 * Login with Email & Password
 */
async function loginWithEmail(email, password) {
    try {
        const userCredential = await auth.signInWithEmailAndPassword(email, password);
        const user = userCredential.user;

        // Ensure user exists in MySQL (auto-sync if missing)
        try {
            await fetch('api/register_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    firebase_uid: user.uid,
                    name: user.displayName || '',
                    email: user.email,
                    phone: null,
                    country: null
                })
            });
        } catch (e) {
            console.error('Login sync error:', e.message);
        }

        return { success: true, user: user };
    } catch (error) {
        let message = 'Login failed';
        switch (error.code) {
            case 'auth/user-not-found': message = 'No account found with this email'; break;
            case 'auth/wrong-password': message = 'Incorrect password'; break;
            case 'auth/invalid-email': message = 'Invalid email address'; break;
            case 'auth/too-many-requests': message = 'Too many attempts. Try again later'; break;
        }
        return { success: false, message: message };
    }
}

/**
 * Logout
 */
async function logout() {
    try {
        await auth.signOut();
        window.location.href = 'index.php';
    } catch (error) {
        console.error('Logout error:', error);
    }
}

/**
 * Get current user's Firebase UID
 */
function getCurrentUid() {
    const user = auth.currentUser;
    return user ? user.uid : null;
}

/**
 * Get current user object
 */
function getCurrentUser() {
    return auth.currentUser;
}

/**
 * Check if user is authenticated, redirect to login if not
 */
function requireAuth() {
    auth.onAuthStateChanged(function(user) {
        if (!user) {
            window.location.href = 'login_new.php';
        }
    });
}

/**
 * Send password reset email
 */
async function resetPassword(email) {
    try {
        await auth.sendPasswordResetEmail(email);
        return { success: true, message: 'Password reset email sent!' };
    } catch (error) {
        return { success: false, message: error.message };
    }
}
