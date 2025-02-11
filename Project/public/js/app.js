addEventListeners();


function addEventListeners() {
  const appealUnblockLink = document.getElementById('appealUnblockPopup');
  if (appealUnblockLink) {
    appealUnblockLink.addEventListener("click", handleAppealUnblock);
  }

  const followersElement = document.querySelector('.list-followers');
  if (followersElement) {
    followersElement.addEventListener('click', handleFollowers);
  }

  const followingElement = document.querySelector('.list-following');
  if (followingElement) {
    followingElement.addEventListener('click', handleFollowing);
  }

  const closeFollowerModal = document.getElementById('closeModal');
  if (closeFollowerModal) {
    closeFollowerModal.addEventListener('click', function () {
      const followersModal = document.getElementById('followersModal');
      followersModal.classList.add('hidden');
    });
  }

  const closeFollowingModal = document.getElementById('closeFollowingModal');
  if (closeFollowingModal) {
    closeFollowingModal.addEventListener('click', function () {
      const followingModal = document.getElementById('followingModal');
      followingModal.classList.add('hidden');
    });
  }

  const userIdMeta = document.querySelector('meta[name="user-id"]');
  if (userIdMeta) {
    const pusherAppKey = document.querySelector('meta[name="pusher-app-key"]').getAttribute('content');
    const pusherCluster = document.querySelector('meta[name="pusher-app-cluster"]').getAttribute('content');

    const pusher = new Pusher(pusherAppKey, {
      cluster: pusherCluster,
      encrypted: true
    });
    setupPusherNotifications(userIdMeta, pusher);
    updateNotificationCount(userIdMeta);
  }

  const markNotificationAsViewed = document.querySelectorAll('.mark-as-viewed-button');
  if (markNotificationAsViewed) {
    markNotificationAsViewed.forEach((button) => {
      button.addEventListener("click", handleViewNotification);
    });
  }

  const prevPageGroupButton = document.getElementById("prev-page-users");
  if (prevPageGroupButton) {
    prevPageGroupButton.addEventListener("click", handlePrevPageUsers);
  }

  const inviteUserButtons = document.querySelectorAll(".invite-user-button");
  inviteUserButtons.forEach((button) => {
    button.addEventListener("click", handleInviteUser);
  });

  const cancelInviteUserButtons = document.querySelectorAll(".cancel-invite-user-button");
  cancelInviteUserButtons.forEach((button) => {
    button.addEventListener("click", handleCancelInviteUser);
  });


  const nextPageGroupButton = document.getElementById("next-page-users");
  if (nextPageGroupButton) {
    nextPageGroupButton.addEventListener("click", handleNextPageUsers);
  }

  const deleteMemberButtons = document.querySelectorAll(
    ".delete-member-button"
  );
  if (deleteMemberButtons) {
    deleteMemberButtons.forEach((button) => {
      button.addEventListener("click", handleDeleteMember);
    });
  }

  const editDescriptionButton = document.getElementById(
    "edit-description-button"
  );
  if (editDescriptionButton) {
    editDescriptionButton.addEventListener("click", showEditDescriptionForm);
  }

  const cancelEditDescriptionButton = document.getElementById(
    "cancel-edit-description"
  );
  if (cancelEditDescriptionButton) {
    cancelEditDescriptionButton.addEventListener(
      "click",
      hideEditDescriptionForm
    );
  }

  const editDescriptionForm = document.getElementById("edit-description-form");
  if (editDescriptionForm) {
    editDescriptionForm.addEventListener(
      "submit",
      handleEditDescriptionFormSubmit
    );
  }

  const invitationsTab = document.getElementById("invitations-tab");
  if (invitationsTab) {
    invitationsTab.addEventListener("click", loadInvitations);
  }

  const loadMoreMembersButton = document.getElementById("load-more-members");
  if (loadMoreMembersButton) {
    loadMoreMembersButton.addEventListener("click", handleLoadMoreMembers);
  }

  const nextPageButton = document.getElementById('next-page-members');
  if (nextPageButton) {
    nextPageButton.addEventListener('click', handleLoadMoreMembers);
  }

  const prevPageButton = document.getElementById('prev-page-members');
  if (prevPageButton) {
    prevPageButton.addEventListener('click', handleLoadMoreMembers);
  }

  showTab('created-groups');

  const joinGroupButton = document.getElementById('join-group-button');
  if (joinGroupButton) {
    joinGroupButton.addEventListener('click', handleJoinGroup);
  }

  const leaveGroupButton = document.getElementById('leave-group-button');
  if (leaveGroupButton) {
    leaveGroupButton.addEventListener('click', handleLeaveGroup);
  }


  const requestJoinGroupButton = document.getElementById('request-join-group-button');
  if (requestJoinGroupButton) {
    requestJoinGroupButton.addEventListener('click', handleRequestJoinGroup);
  }

  const cancelJoinRequestButton = document.getElementById("cancel-join-request-button");
  if (cancelJoinRequestButton) {
    cancelJoinRequestButton.addEventListener("click", handleCancelJoinRequest);
  }

  const acceptJoinRequestButtons = document.querySelectorAll('.accept-join-request');
  acceptJoinRequestButtons.forEach(button => {
    button.addEventListener('click', handleAcceptJoinRequest);
  });

  const declineJoinRequestButtons = document.querySelectorAll('.decline-join-request');
  declineJoinRequestButtons.forEach(button => {
    button.addEventListener('click', handleDeclineJoinRequest);
  });

  const publicGroupsTab = document.getElementById('public-groups-tab');
  if (publicGroupsTab) {
    publicGroupsTab.addEventListener('click', function () {
      showTab('public-groups');
    });
  }

  const privateGroupsTab = document.getElementById('private-groups-tab');
  if (privateGroupsTab) {
    privateGroupsTab.addEventListener('click', function () {
      showTab('private-groups');
    });
  }

  const openCreateGroupModalButton = document.getElementById('open-create-group-modal');
  if (openCreateGroupModalButton) {
    openCreateGroupModalButton.addEventListener('click', handleOpenCreateGroupPopup);
  }

  const closeCreateGroupModalButton = document.getElementById('close-create-group-modal');
  if (closeCreateGroupModalButton) {
    closeCreateGroupModalButton.addEventListener('click', handleCloseCreateGroupPopup);
  }

  const createGroupModalContent = document.getElementById('create-group-modal-content');
  if (createGroupModalContent) {
    createGroupModalContent.addEventListener('click', function (event) {
      event.stopPropagation();
    });
  }

  const createGroupModal = document.getElementById('create-group-modal');
  if (createGroupModal) {
    createGroupModal.addEventListener('click', handleCloseCreateGroupPopup);
  }

  const createGroupForm = document.getElementById('create-group-form');
  if (createGroupForm) {
    createGroupForm.addEventListener('submit', handleCreateGroupFormSubmit);
  }


  const createdGroupsTab = document.getElementById('created-groups-tab');
  if (createdGroupsTab) {
    createdGroupsTab.addEventListener('click', function () {
      showTab('created-groups');
    });
  }

  const joinedGroupsTab = document.getElementById('joined-groups-tab');
  if (joinedGroupsTab) {
    joinedGroupsTab.addEventListener('click', function () {
      showTab('joined-groups');
    });
  }

  const pendingButton = document.getElementById('pending-button');
  if (pendingButton) {
    pendingButton.addEventListener('click', handleCancelFollowRequest);
  }

  const profileFollowButton = document.getElementById('follow-button');
  if (profileFollowButton) {
    profileFollowButton.addEventListener('click', handleProfileFollow);
  }

  const profileUnfollowButton = document.getElementById('unfollow-button');
  if (profileUnfollowButton) {
    profileUnfollowButton.addEventListener('click', handleProfileUnfollow);
  }

  const acceptFollowRequestButtons = document.querySelectorAll('.accept-follow-button');
  if (acceptFollowRequestButtons) {
    acceptFollowRequestButtons.forEach(button => {
      button.addEventListener('click', handleAcceptFollowRequest);
    });
  }

  const rejectFollowRequestButtons = document.querySelectorAll('.reject-follow-button');
  if (rejectFollowRequestButtons) {
    rejectFollowRequestButtons.forEach(button => {
      button.addEventListener('click', handleRejectFollowRequest);
    });
  }

  const postArticles = document.querySelectorAll('.post');
  if (postArticles) {
    postArticles.forEach(post => {
      post.addEventListener('click', redirectToPostPageHandler)
    })
  }

  const blockUserButtons = document.querySelectorAll('.block-user-button');
  if (blockUserButtons) {
    blockUserButtons.forEach(blockUserButton => {
      blockUserButton.addEventListener('click', handleBlockUser);
    });
  }

  const deleteUserButtons = document.querySelectorAll('.delete-user-button');
  if (deleteUserButtons) {
    deleteUserButtons.forEach(deleteUserButton => {
      deleteUserButton.addEventListener('click', handleDeleteUser);
    });
  }

  document.querySelectorAll(".like-button, .comments-button, .post-header, .post-restaurant, .image-slider button, #toggle-dropdown").forEach(function (element) {
    element.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  });

  // Event listeners for role change and form submit
  const role = document.getElementById("role");
  if (role) {
    role.addEventListener("change", handleRoleChange);
  }

  const registerForm = document.getElementById("register-form");
  if (registerForm) {
    registerForm.addEventListener("submit", handleFormSubmit);
  }

  // Event listeners for profile edit functionality
  const editButton = document.getElementById("edit-button");
  const confirmButton = document.getElementById("confirm-button");
  const cancelButton = document.getElementById("cancel-button");

  if (editButton) editButton.addEventListener("click", toggleEditMode);
  if (confirmButton) confirmButton.addEventListener("click", confirmEdit);
  if (cancelButton) cancelButton.addEventListener("click", cancelEdit);

  // Event listeners for restaurant search functionality
  const restaurantSearch = document.getElementById("restaurant_search");
  if (restaurantSearch) {
    restaurantSearch.addEventListener("input", searchRestaurants);
  }

  // Event listeners for post creation and editing
  const postCreator = document.getElementById("create_post");
  if (postCreator) {
    postCreator.addEventListener("submit", sendCreatePostRequest);
  }

  const postEditors = document.querySelectorAll('[id^="edit_post_"]');
  postEditors.forEach(postEditor => {
    postEditor.addEventListener("submit", sendEditPostRequest);
  });

  // Event listeners for delete confirmation
  const cancelDeleteButton = document.getElementById("cancel-delete");
  if (cancelDeleteButton) {
    cancelDeleteButton.addEventListener("click", hideDeleteConfirmation);
  }

  const confirmDeleteButton = document.getElementById("confirm-delete");
  if (confirmDeleteButton) {
    confirmDeleteButton.addEventListener("click", handleConfirmDeleteButton);
  }

  // Event listeners for image input change
  const imageInput = document.getElementById("image-input");
  const profileImage = document.getElementById("profile-image");
  if (imageInput && profileImage) {
    let originalImageSrc = profileImage.src;

    imageInput.addEventListener("change", function () {
      handleImageChange(imageInput, profileImage);
    });

    if (cancelButton) {
      cancelButton.addEventListener("click", function () {
        handleCancelImageChange(profileImage, originalImageSrc, imageInput);
      });
    }

    if (confirmButton) {
      confirmButton.addEventListener("click", function () {
        handleConfirmImageChange(profileImage, originalImageSrc);
      });
    }
  }

  // Event listeners for post image slider
  document.querySelectorAll(".post").forEach(function (post) {
    handlePostImageSlider(post);
  });

  // Event listeners for comments section
  const commentsSections = document.querySelectorAll(".comments-section");
  if (commentsSections) {
    commentsSections.forEach(section => {
      handleCommentsSection(section);
    });
  }

  const postsContainer = document.getElementById('posts');
  if (postsContainer) {
    const baseUrl = postsContainer.getAttribute('data-base-url');
    handlePostLoading(postsContainer, baseUrl);
  }

  // Event listeners for search functionality
  handleSearchFunctionality();

  // Event listeners for likes count
  handleLikesCount();

  // Event listener for the forgot password form submission
  const forgotPasswordForm = document.querySelector('form[action="/send"]');
  if (forgotPasswordForm) {
    forgotPasswordForm.removeEventListener('submit', handleForgotPasswordFormSubmit);
    forgotPasswordForm.addEventListener('submit', handleForgotPasswordFormSubmit);
  }

  // Event listener for the reset password form submission
  const resetPasswordForm = document.getElementById('reset-password-form');
  if (resetPasswordForm) {
    resetPasswordForm.removeEventListener('submit', handleResetPasswordFormSubmit); // Remove any existing listener
    resetPasswordForm.addEventListener('submit', handleResetPasswordFormSubmit); // Add the new listener
  }

  const deleteAccountButton = document.getElementById("delete-account-button");
  if (deleteAccountButton) {
    deleteAccountButton.addEventListener("click", function() {
      const modal = document.getElementById("delete-account-confirmation-modal");
      modal.classList.remove("hidden");
    })
  }

  const cancelDeleteAccountButton = document.getElementById("cancel-delete-account");
  if (cancelDeleteAccountButton) {
    cancelDeleteAccountButton.addEventListener("click", function() {
      const modal = document.getElementById("delete-account-confirmation-modal");
      modal.classList.add("hidden");
    })
  }

  const confirmDeleteAccountButton = document.getElementById("confirm-delete-account");
  if (confirmDeleteAccountButton) {
    confirmDeleteAccountButton.addEventListener("click", hanleDeleteAccount);
  }

  const cancelDeleteCommentButton = document.getElementById("cancel-delete-comment");
  if (cancelDeleteCommentButton) {
    cancelDeleteCommentButton.addEventListener("click", hideDeleteCommentConfirmation);
  }

  const confirmDeleteCommentButton = document.getElementById("confirm-delete-comment");
  if (confirmDeleteCommentButton) {
    confirmDeleteCommentButton.addEventListener("click", confirmDeleteComment);
  }
}

function formatDateTime(dateTimeString) {
  const date = new Date(dateTimeString);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);

  if (diffInSeconds < 60) {
    return `Just now`;
  } else if (diffInSeconds < 3600) {
    const diffInMinutes = Math.floor(diffInSeconds / 60);
    return `${diffInMinutes} minutes ago`;
  } else if (diffInSeconds < 86400) {
    const diffInHours = Math.floor(diffInSeconds / 3600);
    return `${diffInHours} hours ago`;
  } else if (diffInSeconds < 2592000) { // 30 days
    const diffInDays = Math.floor(diffInSeconds / 86400);
    return `${diffInDays} days ago`;
  } else if (diffInSeconds < 31536000) { // 365 days
    const diffInMonths = Math.floor(diffInSeconds / 2592000);
    return `${diffInMonths} months ago`;
  } else {
    const diffInYears = Math.floor(diffInSeconds / 31536000);
    return `${diffInYears} years ago`;
  }
}


function hanleDeleteAccount () {
  const userId = this.getAttribute("data-user-id");
  fetch(`/profile/${userId}/delete`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showStatusMessage(true, 'Account deleted successfully. Redirecting to homepage...');
      setTimeout(() => {
        window.location.href = '/';
      }, 2000);
    } else {
      showStatusMessage(false, data.message || 'Failed to delete the account. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showStatusMessage(false, 'An error occurred while deleting the account. Please try again.');
  });
}


function handlePostLoading(postsContainer, baseUrl) {
  let offset = parseInt(postsContainer.getAttribute('data-offset'), 10) || 0;
  const userId = postsContainer.getAttribute('data-user-id');
  let hasMorePages = true;
  let isLoading = false;

  async function loadMorePosts() {
    if (isLoading || !hasMorePages) return;
    isLoading = true;
    try {
      const url = userId ? `${baseUrl}/${userId}?offset=${offset}` : `${baseUrl}?offset=${offset}`;
      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      const data = await response.json();

      if (data.posts.length) {
        data.posts.forEach(postHtml => {
          const postElement = document.createElement('div');
          postElement.innerHTML = postHtml;
          postsContainer.appendChild(postElement);

          postElement.querySelectorAll('.post').forEach(post => {
            post.addEventListener('click', redirectToPostPageHandler);
          });

          const commentSection = postElement.querySelector(".comments-section")
          if (commentSection) {
            handleCommentsSection(commentSection);
          }
        });

        offset += data.posts.length;
        postsContainer.setAttribute('data-offset', offset);

      } else {
        hasMorePages = false;
      }
    } catch (error) {
      console.error('Error loading more posts:', error);
    } finally {
      isLoading = false;
    }
  }

  function handleScroll() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
      loadMorePosts();
    }
  }

  window.addEventListener('scroll', handleScroll, { passive: true });
}

function redirectToPostPageHandler(event) {
  const postId = event.currentTarget.getAttribute('data-id');
  if (postId) {
    window.location = `/post/${postId}`;
  }
}

// JavaScript Handlers for Login & Register
function handleRoleChange() {
  const restaurantFields = document.getElementById("restaurant-fields");
  if (this.value === "restaurant") {
    restaurantFields.style.display = "block";
  } else {
    restaurantFields.style.display = "none";
  }
}

function handleFormSubmit(event) {
  const role = document.getElementById("role").value;
  if (role === "client") {
    document.getElementById("type_id").removeAttribute("name");
    document.getElementById("capacity").removeAttribute("name");
  } else {
    document.getElementById("type_id").setAttribute("name", "type_id");
    document.getElementById("capacity").setAttribute("name", "capacity");
  }
}

// JavaScript Handlers for Create ReviewPost
function searchRestaurants() {
  const restaurantSearch = document.getElementById("restaurant_search");
  const query = restaurantSearch.value;

  if (query.length < 1) {
    document.getElementById("restaurant_suggestions").innerHTML = "";
    return;
  }

  sendAjaxRequest(
    "GET",
    `/restaurants/search?q=${encodeURIComponent(query)}`,
    null,
    restaurantSearchHandler
  );

  document.addEventListener("click", function (event) {
    if (
      !restaurantSearch.contains(event.target) &&
      !document.getElementById("restaurant_suggestions").contains(event.target)
    ) {
      document.getElementById("restaurant_suggestions").innerHTML = "";
    }
  });
}

function restaurantSearchHandler() {
  if (this.status === 200) {
    const data = JSON.parse(this.responseText);
    const restaurantSuggestions = document.getElementById(
      "restaurant_suggestions"
    );
    restaurantSuggestions.innerHTML = "";
    const baseUrl = window.location.origin;
    data.forEach((restaurant) => {
      const section = document.createElement("section");
      section.classList.add(
        "suggestion-item",
        "p-2",
        "cursor-pointer",
        "hover:bg-gray-200",
        "flex",
        "items-center"
      );

      const img = document.createElement("img");
      img.src = `storage/${restaurant.image}`;
      img.src = `${baseUrl}/storage/${restaurant.image}`;
      img.alt = "Profile Picture";
      img.classList.add("w-8", "h-8", "rounded-full", "mr-2");

      const text = document.createElement("span");
      text.textContent = `${restaurant.name} (${restaurant.username})`;

      section.appendChild(img);
      section.appendChild(text);

      section.addEventListener("click", function () {
        document.getElementById("restaurant_search").value =
          restaurant.username;
        document.getElementById("restaurant_id").value = restaurant.id;
        restaurantSuggestions.innerHTML = "";
      });

      restaurantSuggestions.appendChild(section);
    });
  }
}

// JavaScript handlers for Edit Profile
function toggleEditMode() {
  document.getElementById("edit-button").classList.toggle("hidden");
  document.getElementById("confirm-button").classList.toggle("hidden");
  document.getElementById("cancel-button").classList.toggle("hidden");
  document.getElementById("image-input").classList.toggle("hidden");
  document.getElementById("profile-info").classList.toggle("hidden");
  document.getElementById("edit-info").classList.toggle("hidden");
}

function confirmEdit() {
  const nameInput = document.getElementById("name-input").value.trim();
  const descriptionInput = document.getElementById("description-input").value.trim();
  const imageInput = document.getElementById("image-input").files[0];

  // Client-side validation
  if (!nameInput) {
    showStatusMessage(false, "Name cannot be empty.");
    return;
  }

  if (!descriptionInput) {
    showStatusMessage(false, "Description cannot be empty.");
    return;
  }

  const formData = new FormData();
  formData.append("name", nameInput);
  formData.append("description", descriptionInput);
  if (imageInput) {
    formData.append("image", imageInput);
  }

  fetch("/profile/update", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        return response.json().then((errors) => {
          throw new Error(JSON.stringify(errors));
        });
      }
      return response.json();
    })
    .then((jsonData) => {
      if (jsonData.success) {
        document.getElementById("profile-name").textContent = jsonData.user.name;
        document.getElementById("profile-description").textContent = jsonData.user.description;
        if (jsonData.user.image) {
          document.getElementById("profile-image").src = jsonData.user.image;
        }
        toggleEditMode();
        showStatusMessage(true, "Profile updated successfully");
      } else {
        showStatusMessage(false, "Failed to update profile");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      const errorData = JSON.parse(error.message);
      if (errorData.errors) {
        let errorMessage = "Validation errors:\n";
        for (const [field, messages] of Object.entries(errorData.errors)) {
          errorMessage += `${field}: ${messages.join(", ")}\n`;
        }
        showStatusMessage(false, errorMessage);
      } else {
        showStatusMessage(false, "Failed to update profile: " + errorData.message);
      }
    });
}

function cancelEdit() {
  toggleEditMode();
}

// Handlers for image input change in profile
function handleImageChange(imageInput, profileImage) {
  const file = imageInput.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      profileImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handleCancelImageChange(profileImage, originalImageSrc, imageInput) {
  profileImage.src = originalImageSrc;
  imageInput.value = "";
}

function handleConfirmImageChange(profileImage, originalImageSrc) {
  originalImageSrc = profileImage.src; // Update the original image source
}


// Handlers for post image slider
function handlePostImageSlider(post) {
  const postId = post.getAttribute("data-id");
  let currentImageIndex = 0;
  const images = JSON.parse(
    post.querySelector(".image-slider").getAttribute("data-images")
  );

  function updateDots() {
    images.forEach((image, index) => {
      document
        .getElementById("dot-" + postId + "-" + index)
        .classList.remove("bg-blue-500");
      document
        .getElementById("dot-" + postId + "-" + index)
        .classList.add("bg-gray-300");
    });
    document
      .getElementById("dot-" + postId + "-" + currentImageIndex)
      .classList.remove("bg-gray-300");
    document
      .getElementById("dot-" + postId + "-" + currentImageIndex)
      .classList.add("bg-blue-500");
  }

  function updateImage() {
    const baseUrl = window.location.origin;
    document.getElementById("current-image-" + postId).src =
      baseUrl + "/storage/" + images[currentImageIndex];
    const modalImage = document.getElementById(
      "modal-current-image-" + postId
    );
    if (modalImage) {
      modalImage.src = baseUrl + "/storage/" + images[currentImageIndex];
    }
    updateDots();
  }

  window["prevReviewImage" + postId] = function () {
    currentImageIndex =
      (currentImageIndex - 1 + images.length) % images.length;
    updateImage();
  };

  window["nextReviewImage" + postId] = function () {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    updateImage();
  };

  window["prevInfoImage" + postId] = function () {
    currentImageIndex =
      (currentImageIndex - 1 + images.length) % images.length;
    updateImage();
  };

  window["nextInfoImage" + postId] = function () {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    updateImage();
  };

  window["prevModalImage" + postId] = function () {
    currentImageIndex =
      (currentImageIndex - 1 + images.length) % images.length;
    updateImage();
  };

  window["nextModalImage" + postId] = function () {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    updateImage();
  };
}

function openModal(postId) {
  document.getElementById("comment-modal-" + postId).classList.remove("hidden");
}

function closeModal(postId) {
  document.getElementById("comment-modal-" + postId).classList.add("hidden");
}

function removeEventListeners(selector, event, handler) {
  document.querySelectorAll(selector).forEach(function (element) {
    element.removeEventListener(event, handler);
  });
}

function handleCommentsSection(section) {
  const postId = section.getAttribute("data-post-id");
  let offset = 0;
  const limit = 20;

  loadComments(postId, offset, limit, section);


  // Load more comments on scroll
  section.addEventListener("scroll", function () {
    if (section.scrollTop + section.clientHeight >= section.scrollHeight) {
      offset += limit;
      loadComments(postId, offset, limit, section);
    }
  });

}

function loadComments(postId, offset, limit, section) {
  const loadingIndicator = document.getElementById(
    "loading-indicator-" + postId
  );
  if (loadingIndicator) {
    loadingIndicator.classList.remove("hidden");
  }

  fetch(`/post/${postId}/comments?offset=${offset}&limit=${limit}`)
    .then((response) => response.json())
    .then((comments) => {
      if (comments.length === 0 && offset === 0) {
        const existingNoCommentsMessage = section.querySelector(".no-comments-message");
        if (!existingNoCommentsMessage) {
          const noCommentsMessage = document.createElement("div");
          noCommentsMessage.classList.add("no-comments-message", "text-gray-500");
          noCommentsMessage.textContent = "This post has no comments.";
          section.appendChild(noCommentsMessage);
        }
      } else {
        const existingNoCommentsMessage = section.querySelector(".no-comments-message");
        if (existingNoCommentsMessage) {
          existingNoCommentsMessage.remove();
        }

        if (offset === 0) {
          section.innerHTML = ''; // Clear existing comments to avoid duplicates
        }

        comments.forEach((comment) => {
          const commentDiv = document.createElement("div");
          commentDiv.classList.add("mb-2", "comment-item", "overflow-hidden");
          commentDiv.innerHTML = `
            <div class="w-full">
              <p class="font-bold truncate">${comment.user.name}</p>
              <p class="text-gray-500 text-sm whitespace-nowrap">${formatDateTime(comment.datetime)}</p>
            </div>
            <div class="w-full mt-1">
              <p class="comment-content whitespace-pre-line break-words">${comment.content}</p>
            </div>
            <div class="w-full mt-2 flex flex-wrap">
              <button class="like-comment-button ml-1" data-comment-id="${comment.id}">
                <i class="fa fa-heart text-2xl ${comment.liked_by_user ? 'text-red-500' : 'text-gray-500'}"></i>
              </button>
              <span class="like-count">${comment.likes_count || 0}</span>
              ${comment.is_owner || comment.is_admin ? `
              <button class="edit-comment-button ml-2" data-comment-id="${comment.id}">
                <i class="fa fa-edit"></i>
              </button>
              <button class="delete-comment-button ml-2" data-comment-id="${comment.id}" data-post-id="${postId}">
                <i class="fa fa-trash"></i>
              </button>` : ''}
            </div>
          `;
          section.appendChild(commentDiv);
        });

        offset += limit;
      }

      const likeCommentButtons = section.querySelectorAll('.like-comment-button');
      likeCommentButtons.forEach(button => {
        button.addEventListener('click', handleLikeComment);
      });
      
      const editCommentButtons = section.querySelectorAll('.edit-comment-button');
      editCommentButtons.forEach(button => {
        button.addEventListener('click', handleEditComment);
      });
      
      const deleteCommentButtons = section.querySelectorAll('.delete-comment-button');
      deleteCommentButtons.forEach(button => {
        button.addEventListener('click', handleDeleteComment);
      });

      loadingIndicator.classList.add("hidden");

    })
    .catch((error) => {
      console.error("Error loading comments:", error);
      loadingIndicator.classList.add("hidden");
    });

}

let commentIdToDelete = null;
let postIdToDeleteCommentFrom = null;

function handleDeleteComment() {
  commentIdToDelete = this.getAttribute('data-comment-id');
  postIdToDeleteCommentFrom = this.getAttribute('data-post-id');
  const modal = document.getElementById("delete-comment-confirmation-modal");
  modal.classList.remove("hidden");
}

function hideDeleteCommentConfirmation() {
  commentIdToDelete = null;
  postIdToDeleteCommentFrom = null;
  const modal = document.getElementById("delete-comment-confirmation-modal");
  modal.classList.add("hidden");
}

function confirmDeleteComment() {
  if (commentIdToDelete && postIdToDeleteCommentFrom) {
    sendAjaxRequest(
      "delete",
      `/comment/${commentIdToDelete}/delete`,
      {},
      function () {
        const response = JSON.parse(this.responseText);
        if (response.success) {
          const commentDiv = document.querySelector(`.delete-comment-button[data-comment-id="${commentIdToDelete}"]`).closest('.comment-item');
          commentDiv.remove();

          const commentCountElement = document.getElementById(`comment-count-${postIdToDeleteCommentFrom}`);
          const currentCount = parseInt(commentCountElement.textContent, 10);
          commentCountElement.textContent = currentCount - 1;

          const commentsSection = document.getElementById(`comments-section-${postIdToDeleteCommentFrom}`);
          if (commentsSection && commentsSection.children.length === 0) {
            const noCommentsMessage = document.createElement("div");
            noCommentsMessage.classList.add("no-comments-message", "text-gray-500");
            noCommentsMessage.textContent = "This post has no comments.";
            commentsSection.appendChild(noCommentsMessage);
          }
        } else {
          showStatusMessage(false, "Failed to delete comment");
        }
        hideDeleteCommentConfirmation();
      }
    );
  }
}



function handleEditComment(event, section) {
  const commentId = this.getAttribute('data-comment-id');
  const commentDiv = this.closest('.mb-2');
  const commentContent = commentDiv.querySelector('.comment-content').textContent;

  // Create an edit form
  const editForm = document.createElement('div');
  editForm.classList.add("edit-form");
  editForm.innerHTML = `
    <textarea class="edit-comment-box box border border-gray-300 p-2 w-full">${commentContent}</textarea>
    <div class="mt-2">
      <button class="save-comment-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-comment-id="${commentId}">Save</button>
      <button class="cancel-edit-button bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2">Cancel</button>
    </div>
  `;

  // Replace the comment content with the edit form
  commentDiv.querySelector('.comment-content').replaceWith(editForm);

  // Add event listeners to the save and cancel buttons
  editForm.querySelector('.save-comment-button').addEventListener('click', handleSaveComment);
  editForm.querySelector('.cancel-edit-button').addEventListener('click', handleCancelEdit);
}

function handleSaveComment(event, section) {
  const commentId = this.getAttribute('data-comment-id');
  const editForm = this.closest('.edit-form');
  const newContent = editForm.querySelector('.edit-comment-box').value;

  sendAjaxRequest(
    "post",
    `/comment/${commentId}/edit`,
    { content: newContent },
    function () {
      const response = JSON.parse(this.responseText);
      if (response.success) {
        // Update the comment content
        const commentDiv = editForm.closest('.flex');
        const commentContent = document.createElement('p');
        commentContent.classList.add('comment-content');
        commentContent.textContent = newContent;
        editForm.replaceWith(commentContent);
      } else {
        showStatusMessage(false, "Failed to edit comment");
      }
    }
  );
}

function handleCancelEdit(event) {
  const editForm = this.closest('.edit-form');
  const commentContent = document.createElement('p');
  commentContent.classList.add('comment-content');
  commentContent.textContent = editForm.querySelector('.edit-comment-box').value;
  editForm.replaceWith(commentContent);
}

function handleLikeComment(event, section) {

  event.stopPropagation();

  var commentId = this.getAttribute('data-comment-id');
  var likeCountSpan = this.nextElementSibling;
  var heartIcon = this.querySelector('i');

  fetch('/like-comment/' + commentId, {
    method: 'POST',
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }
      return response.json();
    })
    .then(data => {

      if (data.success) {
        likeCountSpan.textContent = data.like_count; // Update to use like_count
        if (data.liked) {
          heartIcon.classList.remove('text-gray-500');
          heartIcon.classList.add('text-red-500');
        } else {
          heartIcon.classList.remove('text-red-500');
          heartIcon.classList.add('text-gray-500');
        }
      }
    })
    .catch(error => {
      console.error('Error liking comment:', error);
    });
}

function submitComment(postId) {
  const commentBox = document.getElementById("comment-box-" + postId);
  const comment = commentBox.value;

  sendAjaxRequest(
    "post",
    `/post/${postId}/comment`,
    { content: comment },
    function () {
      const response = JSON.parse(this.responseText);
      if (response.success) {

        // Clear the comment box after submission
        commentBox.value = "";
        // Optionally, you can append the new comment to the comments section
        const commentsSection = document.querySelector(
          `#comments-section-${postId}`
        );

        // Remove the "This post has no comments." message if it exists
        const noCommentsMessage = commentsSection.querySelector(
          ".no-comments-message"
        );
        if (noCommentsMessage) {
          noCommentsMessage.remove();
        }

        const comment = response.comment;
        const section = document.getElementById(`comments-section-${postId}`);

        const newComment = document.createElement("div");
        newComment.classList.add("mb-2", "comment-item", "overflow-hidden");
        newComment.innerHTML = `
          <div class="w-full">
            <p class="font-bold truncate">${comment.user.name}</p>
            <p class="text-gray-500 text-sm whitespace-nowrap">${formatDateTime(comment.datetime)}</p>
          </div>
          <div class="w-full mt-1">
            <p class="comment-content whitespace-pre-line break-words">${comment.content}</p>
          </div>
          <div class="w-full mt-2 flex flex-wrap">
            <button class="like-comment-button ml-1" data-comment-id="${comment.id}">
              <i class="fa fa-heart text-2xl text-gray-500"></i>
            </button>
            <span class="like-count">0</span>
            <button class="edit-comment-button ml-2" data-comment-id="${comment.id}">
              <i class="fa fa-edit"></i>
            </button>
            <button class="delete-comment-button ml-2" data-comment-id="${comment.id}" data-post-id="${postId}">
              <i class="fa fa-trash"></i>
            </button>
          </div>
        `;
        commentsSection.appendChild(newComment);

        // Add event listeners to the new comment buttons
        newComment.querySelector('.like-comment-button').addEventListener('click', handleLikeComment);
        newComment.querySelector('.edit-comment-button').addEventListener('click', handleEditComment);
        newComment.querySelector('.delete-comment-button').addEventListener('click', handleDeleteComment);

        // Update the comment count
        const commentCountElement = document.getElementById(`comment-count-${postId}`);
        const currentCount = parseInt(commentCountElement.textContent, 10);
        commentCountElement.textContent = currentCount + 1;

        // Auto scroll to the bottom of the comments section
        commentsSection.scrollTop = commentsSection.scrollHeight;
      } else {
        showStatusMessage(false, "Failed to add comment");
      }
    }
  );
}

// like unlike post
function like_unlike_post(post_id, like_button) {
  fetch(`/like-post/${post_id}`, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.text()) // Get the response as text
    .then((text) => {

      const data = JSON.parse(text); // Parse the JSON
      if (data.success) {
        const likesCountElement = document.getElementById(`likes-count-${post_id}`);
        let likesCount = parseInt(likesCountElement.textContent);

        if (data.liked) {
          like_button.querySelector("i").classList.remove("text-gray-500");
          like_button.querySelector("i").classList.add("text-red-500");
          likesCount += 1;
        } else {
          like_button.querySelector("i").classList.remove("text-red-500");
          like_button.querySelector("i").classList.add("text-gray-500");
          likesCount -= 1;
        }

        likesCountElement.textContent = likesCount;
      } else {
        showStatusMessage(false, "Failed to like/unlike post");
      }
    })
    .catch((error) => {
      console.error("Error liking post:", error);
      showStatusMessage(false, "Failed to like/unlike post");
    });
}

function fetchLikesCount(postId) {
  fetch(`/likes/${postId}`)
    .then((response) => response.json())
    .then((data) => {
      const likesCountElement = document.getElementById(
        `likes-count-${postId}`
      );
      const likesCount = data.length;
      likesCountElement.textContent = likesCount;
    })
    .catch((error) => {
      console.error("Error fetching likes count:", error);
    });
}

// Handlers for likes count
function handleLikesCount() {
  const aux = document.querySelectorAll(".likes-count");
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      if (mutation.type === "childList") {
        mutation.addedNodes.forEach(function (node) {
          if (node.classList && node.classList.contains("post")) {
            const postId = node.getAttribute("data-id");
            if (aux.length) {
              fetchLikesCount(postId);
            }
          }
        });
      }
    });
  });

  const config = { childList: true, subtree: true };
  observer.observe(document.body, config);

  // Initial fetch for existing posts
  if (aux.length) {
    document.querySelectorAll(".post").forEach(function (post) {
      const postId = post.getAttribute("data-id");
      fetchLikesCount(postId);
    });
  }
}

let postIdToDelete = null;

function toggleDropdown(postId) {
  const dropdown = document.getElementById(`dropdown-${postId}`);
  const isHidden = dropdown.classList.contains('hidden');

  if (isHidden) {
    dropdown.classList.remove('hidden');
    document.addEventListener('click', handleClickOutsideDropdown);
  } else {
    dropdown.classList.add('hidden');
    document.removeEventListener('click', handleClickOutsideDropdown);
  }
}

function handleClickOutsideDropdown(event) {
  const openDropdowns = document.querySelectorAll('.dropdown-menu:not(.hidden)');
  openDropdowns.forEach(dropdown => {
    if (!dropdown.contains(event.target) && !dropdown.previousElementSibling.contains(event.target)) {
      dropdown.classList.add('hidden');
      document.removeEventListener('click', handleClickOutsideDropdown);
    }
  });
}

function showDeleteConfirmation(postId) {
  postIdToDelete = postId;
  const modal = document.getElementById("delete-confirmation-modal");
  modal.classList.remove("hidden");
}

function hideDeleteConfirmation() {
  postIdToDelete = null;
  const modal = document.getElementById("delete-confirmation-modal");
  modal.classList.add("hidden");
}

function handleConfirmDeleteButton() {
  if (postIdToDelete) {
    deletePost(postIdToDelete);
    hideDeleteConfirmation();
    const postCount = document.getElementById("post-count");
    if (postCount) {
      postCount.textContent = parseInt(postCount.textContent) - 1;
    }
  }
}

function deletePost(postId) {
  sendAjaxRequest("DELETE", `/post/${postId}`, null, function () {
    if (this.status === 200) {
      const data = JSON.parse(this.responseText);
      if (data.success) {
        const postElement = document.querySelector(
          `article[data-id="${postId}"]`
        );

        if (postElement) {
          postElement.remove();
        }

        if (window.location.pathname === `/post/${postId}`) {
          window.location.href = '/';
        } else if (window.location.pathname.includes("/profile/")) {
          const posts = document.getElementById("posts");
          const postArticles = posts ? posts.querySelectorAll(":scope > article") : [];
          if (posts && postArticles.length === 0) {
            posts.innerHTML = "";
            const noPostSection = document.createElement("section");
            noPostSection.classList.add("flex", "justify-center", "items-center", "h-32");

            const noPostsMessage = document.createElement("p");
            noPostsMessage.classList.add("text-gray-600");

            const userName = document.getElementById("profile-name");
            if (userName) {
              noPostsMessage.textContent = `${userName.textContent} does not have any post yet!`;
            } else {
              noPostsMessage.textContent = `This User does not have any post yet!`;
            }

            noPostSection.appendChild(noPostsMessage);
            posts.appendChild(noPostSection);
          }
        }
      } else {
        showStatusMessage(false, "Failed to delete post");
      }
    } else {
      showStatusMessage(false, "Failed to delete post");
    }
  });
}

function fetchSearchResults(query, category, type) {
  sendAjaxRequest(
    "get",
    `/search?query=${encodeURIComponent(
      query
    )}&type=${category}&searchType=${type}`,
    null,
    searchResultsHandler
  );
}

function searchResultsHandler() {
  if (this.status != 200) {
    console.error("Error fetching search results:", this.statusText);
    return;
  }
  const results = JSON.parse(this.responseText);
  displaySearchResults(results);
}

function displaySearchResults(results) {
  const searchResults = document.getElementById("search-results");
  searchResults.innerHTML = "";
  if (results.length > 0) {
    const baseUrl = window.location.origin;
    results.forEach((result) => {
      const resultItem = document.createElement("section");
      resultItem.classList.add(
        "p-2",
        "border-b",
        "hover:bg-gray-100",
        "flex",
        "items-center"
      );

      if (result.name.length > 50) {
        result.name = result.name.substring(0, 47) + "...";
      }

      resultItem.innerHTML = `
        ${result.image
          ? `<img src="${baseUrl}/storage/${result.image}" alt="${result.name}" style="width: 3rem; height: 3rem;" class="rounded-full mr-2 object-cover">`
          : ""
        }
        <a href="${result.url}" class="block text-gray-800">
          ${result.author
          ? `<span class="font-bold">${result.author}</span>: `
          : ""
        }
          <span class="italic">${result.name}</span>
        </a>
      `;

      searchResults.appendChild(resultItem);
    });
    searchResults.classList.remove("hidden");
  } else {
    searchResults.classList.add("hidden");
  }
}

// Handlers for search functionality
function handleSearchFunctionality() {
  const searchInput = document.getElementById("search-input");
  const searchType = document.getElementById("search-type");
  const searchResults = document.getElementById("search-results");
  const searchContainer = document.getElementById("search-container");
  const searchControls = document.getElementById("search-controls");
  let searchCategory = "users";

  if (searchInput && searchResults && searchControls && searchContainer) {
    searchInput.addEventListener("input", function () {
      const query = searchInput.value.trim();
      const type = searchType.value;
      if (query.length > 0) {
        searchContainer.classList.remove("hidden");
        fetchSearchResults(query, searchCategory, type);
      } else {
        searchContainer.classList.add("hidden");
        searchResults.innerHTML = "";
      }
    });

    searchType.addEventListener("change", function () {
      const query = searchInput.value.trim();
      const type = searchType.value;
      if (query.length > 0) {
        fetchSearchResults(query, searchCategory, type);
      }
    });

    document
      .getElementById("search-users")
      .addEventListener("click", function () {
        searchCategory = "users";
        const query = searchInput.value.trim();
        const type = searchType.value;
        if (query.length > 0) {
          fetchSearchResults(query, searchCategory, type);
        }
      });

    document
      .getElementById("search-posts")
      .addEventListener("click", function () {
        searchCategory = "posts";
        const query = searchInput.value.trim();
        const type = searchType.value;
        if (query.length > 0) {
          fetchSearchResults(query, searchCategory, type);
        }
      });
  }
}


function openCreatePostModal() {
  document.getElementById('create-post-modal').classList.remove('hidden');
}

function closeCreatePostModal() {
  document.getElementById('create-post-modal').classList.add('hidden');
}

function setRating(rating, postId = null) {
  const stars = postId ? document.querySelectorAll(`#rating-${postId} svg`) : document.querySelectorAll('#rating svg');
  stars.forEach((star, index) => {
    if (index < rating) {
      star.classList.remove('text-gray-300');
      star.classList.add('text-yellow-500');
    } else {
      star.classList.remove('text-yellow-500');
      star.classList.add('text-gray-300');
    }
  });
  const ratingInput = postId ? document.getElementById(`rating-value-${postId}`) : document.getElementById('rating-value');
  if (ratingInput) {
    ratingInput.value = rating;
  } else {
    console.error(`Rating input not found`);
  }
}

function showUploadedImages(event, postId = null) {
  const files = event.target.files;
  const previewId = postId ? 'image-preview-' + postId : 'image-preview';
  const preview = document.getElementById(previewId);
  preview.innerHTML = '';

  if (files.length > 6) {
    showStatusMessage(false, 'You can only upload a maximum of 6 images.');
    event.target.value = '';
    return;
  }

  Array.from(files).forEach(file => {
    const reader = new FileReader();
    reader.onload = function (e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.classList.add('w-full', 'h-32', 'object-contain', 'rounded-lg', 'shadow-md');
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
}

function openEditModal(postId, postType, rating) {
  const modal = document.getElementById('edit-post-modal-' + postId);
  if (!modal) {
    console.error(`Modal with ID edit-post-modal-${postId} not found`);
    return;
  }

  const form = modal.querySelector('form');
  if (!form) {
    console.error(`Form inside modal with ID edit-post-modal-${postId} not found`);
    return;
  }

  const contentElement = document.querySelector(`article[data-id="${postId}"] .post-content p`);
  if (!contentElement) {
    console.error(`Content element for post ID ${postId} not found`);
    return;
  }
  const content = contentElement.innerText;

  const images = document.querySelectorAll(`article[data-id="${postId}"] .post-images img`);
  if (images.length === 0) {
    console.error(`Image elements for post ID ${postId} not found`);
    return;
  }

  form.action = `/posts/${postId}`;
  form.querySelector('textarea[name="content"]').value = content;
  form.querySelector(`#image-preview-${postId}`).innerHTML = '';
  images.forEach(img => {
    const imgElement = document.createElement('img');
    imgElement.src = img.src;
    imgElement.classList.add('w-full', 'h-32', 'object-contain', 'rounded-lg', 'shadow-md');
    form.querySelector(`#image-preview-${postId}`).appendChild(imgElement);
  });
  if (postType === 'review') {
    form.querySelector('#rating-' + postId).classList.remove('hidden');
    form.querySelector(`input[name="rating"]`).value = rating;
    setRating(rating, postId);
  }

  modal.classList.remove('hidden');
}

function closeEditModal(postId) {
  document.getElementById('edit-post-modal-' + postId).classList.add('hidden');
}

function sendEditPostRequest(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST', // Use POST method
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'X-HTTP-Method-Override': 'PUT' // Override to PUT
    },
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        return response.json().then(errors => {
          throw new Error(JSON.stringify(errors));
        });
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        showStatusMessage(false, 'Failed to update post: ' + data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      const errorData = JSON.parse(error.message);
      if (errorData.errors) {
        let errorMessage = "Validation errors:\n";
        for (const [field, messages] of Object.entries(errorData.errors)) {
          errorMessage += `${field}: ${messages.join(", ")}\n`;
        }
        showStatusMessage(false, errorMessage);
      } else {
        showStatusMessage(false, "Failed to update post: " + errorData.message);
      }
    });
}

function sendCreatePostRequest(event) {
  event.preventDefault();

  let content = this.querySelector('textarea[name=content]').value;
  let images = this.querySelector('input[name="images[]"]').files;
  let rating = this.querySelector('input[name=rating]') ? this.querySelector('input[name=rating]').value : null;
  let restaurantId = this.querySelector('input[name=restaurant_id]') ? this.querySelector('input[name=restaurant_id]').value : null;
  let groupId = this.querySelector('input[name=group_id]') ? (this.querySelector('input[name=group_id]').value ? this.querySelector('input[name=group_id]').value : null) : null;

  if (content === '' && images.length === 0) {
    showStatusMessage(false, 'You cannot create an empty post');
    return;
  }

  let formData = new FormData();
  formData.append('content', content);
  for (let i = 0; i < images.length; i++) {
    formData.append('images[]', images[i]);
  }
  if (rating) {
    formData.append('rating', rating);
  }
  if (restaurantId) {
    formData.append('restaurant_id', restaurantId);
  }
  if (groupId) {
    formData.append('group_id', groupId);
  }

  fetch('/api/posts/create', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        return response.json().then(errors => {
          throw new Error(JSON.stringify(errors));
        });
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        document.getElementById('create_post').reset();
        closeCreatePostModal();
        window.location = '/post/' + data.post.id;
      } else {
        showStatusMessage(false, data.error);
        console.error('Error creating post:', data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      const errorData = JSON.parse(error.message);
      if (errorData.errors) {
        let errorMessage = "Validation errors:\n";
        for (const [field, messages] of Object.entries(errorData.errors)) {
          errorMessage += `${field}: ${messages.join(", ")}\n`;
        }
        showStatusMessage(false, errorMessage);
      } else {
        showStatusMessage(false, "Failed to create post: " + errorData.message);
      }
    });
}

// JavaScript Auxiliary Functions
function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data)
    .map(function (k) {
      return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
    })
    .join("&");
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader(
    "X-CSRF-TOKEN",
    document.querySelector('meta[name="csrf-token"]').content
  );
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.addEventListener("load", handler);
  request.send(encodeForAjax(data));
}

function handleBlockUser(event) {
  const userId = this.getAttribute('data-user-id');
  const button = this;

  fetch(`/manage-users/block/${userId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      const statusMessage = document.getElementById('status-message');
      if (data.success) {
        if (button.textContent.trim() === 'Block') {
          showStatusMessage(true, 'User was blocked successfully.');
          button.textContent = 'Unblock';
          button.closest('tr').querySelector('td:nth-child(3)').textContent = 'Blocked';
        } else {
          showStatusMessage(true, 'User was unblocked successfully.');
          button.textContent = 'Block';
          button.closest('tr').querySelector('td:nth-child(3)').textContent = 'Active';

          const userIdMeta = document.querySelector('meta[name="user-id"]');
          updateNotificationCount(userIdMeta);
        }
      } else {
        showStatusMessage(false, 'Failed to block user');
      }
    })
    .catch(error => {
      showStatusMessage(false, 'Failed to update user status');
    });
}

function handleDeleteUser(event) {
  const userId = this.getAttribute('data-user-id');
  const button = this;

  if (confirm('Are you sure you want to delete this user?')) {
    fetch(`/manage-users/delete/${userId}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })
      .then(response => response.json())
      .then(data => {
        const statusMessage = document.getElementById('status-message');
        if (data.success) {
          showStatusMessage(true, 'User was deleted successfully.');
          button.closest('tr').remove();
        } else {
          showStatusMessage(false, 'Failed to delete user');
        }
      })
      .catch(error => {
        console.error('Error anonymizing user:', error);
      });
  }
}

function handleAcceptFollowRequest(event) {
  event.preventDefault();
  const requesterId = this.closest('li').getAttribute('data-requester-id');
  fetch(`/requests/accept/${requesterId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      const statusMessage = document.getElementById('status-message');
      if (data.success) {
        showStatusMessage(true, 'Accepted the follow request successfully.');
        this.closest('li').remove();
        const remainingRequests = document.querySelectorAll('.follow-requests-list li');
        if (remainingRequests.length === 0) {
          const followRequestsList = document.querySelector('.follow-requests-list');
          followRequestsList.innerHTML = '<p class="text-gray-600">You don\'t have any follow requests.</p>';
        }

      } else {
        showStatusMessage(false, 'Failed to accept the follow request.');
      }
    })
    .catch(error => console.error('Error:', error));
}


function handleAcceptInvitation(event) {
  const button = event.target;
  const invitationId = button.getAttribute("data-invitation-id");

  fetch(`/invitations/${invitationId}/accept`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, "Invitation accepted successfully.");
        loadInvitations();
      } else {
        showStatusMessage(false, "Failed to accept invitation.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function handleRejectInvitation(event) {
  const button = event.target;
  const invitationId = button.getAttribute("data-invitation-id");

  fetch(`/invitations/${invitationId}/reject`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, "Invitation rejected successfully.");

        loadInvitations();
      } else {
        showStatusMessage(false, "Failed to reject invitation.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}



function handleRejectFollowRequest(event) {
  event.preventDefault();
  const requesterId = this.closest('li').getAttribute('data-requester-id');
  fetch(`/requests/reject/${requesterId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      const statusMessage = document.getElementById('status-message');
      if (data.success) {
        showStatusMessage(true, 'Rejected the follow request successfully.');
        this.closest('li').remove();
        const remainingRequests = document.querySelectorAll('.follow-requests-list li');
        if (remainingRequests.length === 0) {
          const followRequestsList = document.querySelector('.follow-requests-list');
          followRequestsList.innerHTML = '<p class="text-gray-600">You don\'t have any follow requests.</p>';
        }

      } else {
        showStatusMessage(false, 'Failed to reject the follow request.');
      }
      setTimeout(() => {
        statusMessage.classList.add('hidden');
      }, 3000);
    })
    .catch(error => console.error('Error:', error));
}


function handleProfileFollow(event) {
  event.preventDefault();
  const followButton = event.target;
  const userId = followButton.getAttribute('data-user-id');
  fetch(`/profile/${userId}/follow`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (data.userType === 'client') {
          showStatusMessage(true, 'Follow request sent successfully.');
          followButton.textContent = 'Pending';
          followButton.id = 'pending-button';
          followButton.classList.remove('bg-primary');
          followButton.classList.add('bg-gray-500');
          followButton.removeEventListener('click', handleProfileFollow);
          followButton.addEventListener('click', handleCancelFollowRequest);
        } else if (data.userType === 'restaurant') {
          showStatusMessage(true, 'Follow request sent successfully.');
          followButton.textContent = 'Unfollow';
          followButton.id = 'unfollow-button';
          followButton.classList.remove('bg-primary');
          followButton.classList.add('bg-secondary');
          followButton.removeEventListener('click', handleProfileFollow);
          followButton.addEventListener('click', handleProfileUnfollow);
        }
      } else {
        showStatusMessage(false, 'Failed to send follow request.');
      }
    })
    .catch(error => console.error('Error:', error));
}

function handleProfileUnfollow(event) {
  event.preventDefault();
  const unfollowButton = event.target;
  const userId = unfollowButton.getAttribute('data-user-id');
  fetch(`/profile/${userId}/unfollow`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, 'Unfollowed the user successfully.');
        unfollowButton.textContent = 'Follow';
        unfollowButton.id = 'follow-button';
        unfollowButton.classList.remove('bg-secondary');
        unfollowButton.classList.add('bg-primary');
        unfollowButton.removeEventListener('click', handleProfileUnfollow);
        unfollowButton.addEventListener('click', handleProfileFollow);
      } else {
        showStatusMessage(false, 'Failed to unfollow the user.');
      }
    })
    .catch(error => console.error('Error:', error));
}

function handleCancelFollowRequest(event) {
  event.preventDefault();
  const pendingButton = event.target;
  const userId = pendingButton.getAttribute('data-user-id');
  fetch(`/profile/${userId}/cancel-follow-request`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      const statusMessage = document.getElementById('status-message');
      if (data.success) {
        showStatusMessage(true, 'Follow request cancelled successfully.');
        pendingButton.textContent = 'Follow';
        pendingButton.id = 'follow-button';
        pendingButton.classList.remove('bg-gray-500');
        pendingButton.classList.add('bg-primary');
        pendingButton.removeEventListener('click', handleCancelFollowRequest);
        pendingButton.addEventListener('click', handleProfileFollow);
      } else {
        showStatusMessage(false, 'Failed to cancel the follow request.');
      }
    })
    .catch(error => console.error('Error:', error));
}

function showStatusMessage(isSuccess, message) {
  const statusMessage = document.getElementById('status-message');
  statusMessage.textContent = message;
  statusMessage.classList.remove('hidden', 'bg-green-500', 'bg-red-500', 'text-white');

  if (isSuccess) {
    statusMessage.classList.add('bg-green-500', 'text-white');
  } else {
    statusMessage.classList.add('bg-red-500', 'text-white');
  }

  setTimeout(() => {
    statusMessage.classList.add('hidden');
  }, 3000);
}

function handleOpenCreateGroupPopup() {
  document.getElementById('create-group-modal').classList.remove('hidden');
}

function handleCloseCreateGroupPopup() {
  document.getElementById('create-group-modal').classList.add('hidden');
}

function handleCreateGroupFormSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        handleCloseCreateGroupPopup();

        const newGroupItem = document.createElement('li');
        newGroupItem.classList.add('mb-4', 'p-4', 'bg-gray-100', 'rounded-lg', 'shadow');
        newGroupItem.innerHTML = `
          <h3 class="text-lg font-bold">${data.group.name}</h3>
          <p class="text-gray-600">${data.group.description}</p>
          <a href="/groups/${data.group.id}" class="text-blue-500 hover:underline">View Group</a>
        `;

        const createdGroupsList = document.querySelector('#created-groups ul');
        if (createdGroupsList) {
          createdGroupsList.appendChild(newGroupItem.cloneNode(true));
        } else {
          const createdGroupsSection = document.querySelector('#created-groups section');
          createdGroupsSection.innerHTML = '<ul></ul>';
          createdGroupsSection.querySelector('ul').appendChild(newGroupItem.cloneNode(true));
        }

        const joinedGroupsList = document.querySelector('#joined-groups ul');
        if (joinedGroupsList) {
          joinedGroupsList.appendChild(newGroupItem.cloneNode(true));
        } else {
          const joinedGroupsSection = document.querySelector('#joined-groups section');
          joinedGroupsSection.innerHTML = '<ul></ul>';
          joinedGroupsSection.querySelector('ul').appendChild(newGroupItem.cloneNode(true));
        }

        if (data.group.is_public !== "0") {
          const publicGroupsList = document.querySelector('#public-groups ul');
          if (publicGroupsList) {
            publicGroupsList.appendChild(newGroupItem.cloneNode(true));
          } else {
            const publicGroupsSection = document.querySelector('#public-groups section');
            publicGroupsSection.innerHTML = '<ul></ul>';
            publicGroupsSection.querySelector('ul').appendChild(newGroupItem.cloneNode(true));
          }
        } else {
          const privateGroupsList = document.querySelector('#private-groups ul');
          if (privateGroupsList) {
            privateGroupsList.appendChild(newGroupItem.cloneNode(true));
          } else {
            const privateGroupsSection = document.querySelector('#private-groups section');
            privateGroupsSection.innerHTML = '<ul></ul>';
            privateGroupsSection.querySelector('ul').appendChild(newGroupItem.cloneNode(true));
          }
        }


      } else {
        showStatusMessage(false, data.message || 'Failed to create the group. Please try again.');
      }
    })
    .catch(error => {
      console.error('Error creating group:', error);
      showStatusMessage(false, 'Failed to create the group. Please try again.');
    });
}

function showTab(tabId) {
  const tabs = document.querySelectorAll('.tab-content');
  tabs.forEach(tab => {
    tab.classList.add('hidden');
  });

  const activeTab = document.getElementById(tabId);
  if (activeTab) {
    activeTab.classList.remove('hidden');
  }

  const tabButtons = document.querySelectorAll('.tab-button');
  tabButtons.forEach(button => {
    button.classList.remove('bg-[#405865]', 'text-white');
    button.classList.add('bg-gray-200', 'text-gray-700');
  });

  const activeTabButton = document.getElementById(`${tabId}-tab`);
  if (activeTabButton) {
    activeTabButton.classList.add('bg-[#405865]', 'text-white');
    activeTabButton.classList.remove('bg-gray-200', 'text-gray-700');
  }
}

function handleJoinGroup(event) {
  const button = event.target;
  const groupId = button.getAttribute('data-group-id');

  fetch(`/groups/${groupId}/join`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.textContent = 'Leave Group';
        button.classList.remove('bg-primary', 'text-white');
        button.classList.add('bg-tertiary', 'text-secondary');
        button.id = 'leave-group-button';
        button.removeEventListener('click', handleJoinGroup);
        button.addEventListener('click', handleLeaveGroup);

        // Add new member entry to the group members list
        const groupMembersList = document.getElementById('group-members-list');
        const newMember = document.createElement('li');
        newMember.classList.add('mb-4', 'p-4', 'bg-gray-100', 'rounded-lg', 'shadow', 'flex', 'justify-between', 'items-center');
        newMember.setAttribute('data-member-id', data.newMember.id);
        newMember.innerHTML = `
              <div>
                  <h3 class="text-lg font-bold">${data.newMember.name ?? 'Name not available'}</h3>
                  <p class="text-gray-600">${data.newMember.email ?? 'Email not available'}</p>
              </div>
              ${data.isOwner ? `
              <button class="delete-member-button text-secondary px-4 py-2" data-group-id="${groupId}" data-member-id="${data.newMember.id}">
                  <i class="fas fa-trash mr-2"></i>
              </button>` : ''}
          `;
        groupMembersList.appendChild(newMember);

        // Add event listener for the delete button if the user is the owner
        if (data.isOwner) {
          const deleteButton = newMember.querySelector('.delete-member-button');
          deleteButton.addEventListener('click', handleDeleteMember);
        }

        const hiddenLikes = document.querySelectorAll(".hidden-likes");
        const hiddenComments = document.querySelectorAll(".hidden-comments");
        hiddenLikes.forEach(like => {
          like.classList.remove("hidden")
        })
        hiddenComments.forEach(comment => {
          comment.classList.remove("hidden")
        })

      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'An error occurred while trying to join the group.');
    });
}

function handleLeaveGroup(event) {
  const button = event.target;
  const groupId = button.getAttribute("data-group-id");
  const isPublic = button.getAttribute("data-is-public") === "1";

  fetch(`/groups/${groupId}/leave`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, data.message);

        // Remove the member from the group members list
        const memberItem = document.querySelector(`li[data-member-id="${data.memberId}"]`);
        if (memberItem) {
          memberItem.remove();
        }

        // Hide the group posts section if the group is private
        if (!isPublic) {
          const groupPostsSection = document.querySelector(".group_posts");
          if (groupPostsSection) {
            groupPostsSection.classList.add("hidden");
          }
        }

        if (isPublic) {

          const hiddenLikes = document.querySelectorAll(".hidden-likes");
          const hiddenComments = document.querySelectorAll(".hidden-comments");
          hiddenLikes.forEach(like => {
            like.classList.add("hidden")
          })
          hiddenComments.forEach(comment => {
            comment.classList.add("hidden")
          })

          button.textContent = "Join Group";
          button.classList.remove("bg-tertiary", "text-secondary");
          button.classList.add("bg-primary", "text-white");
          button.id = "join-group-button";
          button.removeEventListener("click", handleLeaveGroup);
          button.addEventListener("click", handleJoinGroup);
        } else {
          button.textContent = "Request to Join Group";
          button.classList.remove("bg-tertiary", "text-secondary");
          button.classList.add("bg-primary", "text-white");
          button.id = "request-join-group-button";
          button.removeEventListener("click", handleLeaveGroup);
          button.addEventListener("click", handleRequestJoinGroup);
        }

        window.location.href = "/groups";
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred while trying to leave the group.");
    });
}

function handleRequestJoinGroup(event) {
  const button = event.target;
  const groupId = button.getAttribute('data-group-id');

  fetch(`/groups/${groupId}/request-join`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.textContent = 'Cancel Request';
        button.disabled = true;
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'An error occurred while trying to request to join the group.');
    });
}

function handleCancelJoinRequest(event) {
  const button = event.target;
  const groupId = button.getAttribute('data-group-id');

  fetch(`/groups/${groupId}/cancel-request`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.textContent = 'Request to Join Group';
        button.id = 'request-join-group-button';
        button.removeEventListener('click', handleCancelJoinRequest);
        button.addEventListener('click', handleRequestJoinGroup);
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'An error occurred while trying to cancel the join request.');
    });
}

function handleAcceptJoinRequest(event) {
  const button = event.target;
  const groupId = button.getAttribute('data-group-id');
  const clientId = button.getAttribute('data-client-id');

  fetch(`/groups/${groupId}/accept/${clientId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        const requestItem = button.closest('li');
        if (requestItem) {
          requestItem.remove();
        }
        const joinRequestsList = document.querySelector('.join-requests-list');
        if (joinRequestsList && joinRequestsList.children.length === 0) {
          const noRequestsMessage = document.createElement('p');
          noRequestsMessage.classList.add('text-gray-600');
          noRequestsMessage.textContent = 'No join requests for this group.';
          joinRequestsList.parentNode.appendChild(noRequestsMessage);
        }

        // Add the new member to the group members list
        const membersList = document.getElementById("group-members-list");
        const newMemberItem = document.createElement("li");
        newMemberItem.classList.add("mb-4", "p-4", "bg-gray-100", "rounded-lg", "shadow", "flex", "justify-between", "items-center");
        newMemberItem.innerHTML = `
          <div>
            <h3 class="text-lg font-bold">${data.member.name}</h3>
            <p class="text-gray-600">${data.member.email}</p>
          </div>
          <button class="delete-member-button text-secondary px-4 py-2" data-group-id="${groupId}" data-member-id="${clientId}">
            <i class="fas fa-trash mr-2"></i>
          </button>
        `;
        membersList.appendChild(newMemberItem);

        // Re-add event listener for the new delete button
        newMemberItem.querySelector(".delete-member-button").addEventListener("click", handleDeleteMember);

        const inviteListItem = document.querySelector(`.invite-user-button[data-user-id="${clientId}"]`);
        if (inviteListItem) {
          inviteListItem.closest('li').remove();
        }
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'An error occurred while trying to accept the join request.');
    });
}

function handleDeclineJoinRequest(event) {
  const button = event.target;
  const groupId = button.getAttribute('data-group-id');
  const clientId = button.getAttribute('data-client-id');

  fetch(`/groups/${groupId}/decline/${clientId}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
        const requestItem = button.closest('li');
        if (requestItem) {
          requestItem.remove();
        }
        const joinRequestsList = document.querySelector('.join-requests-list');
        if (joinRequestsList && joinRequestsList.children.length === 0) {
          const noRequestsMessage = document.createElement('p');
          noRequestsMessage.classList.add('text-gray-600');
          noRequestsMessage.textContent = 'No join requests for this group.';
          joinRequestsList.parentNode.appendChild(noRequestsMessage);
        }
      } else {
        showStatusMessage(false, data.message);

      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'An error occurred while trying to decline the join request.');
    });
}

function handleLoadMoreMembers(event) {
  const button = event.target;
  const page = button.getAttribute('data-next-page') || button.getAttribute('data-prev-page');
  const groupId = button.getAttribute('data-group-id');

  fetch(`/groups/${groupId}/members?page=${page}`)
    .then(response => response.json())
    .then(data => {
      const membersList = document.getElementById('group-members-list');
      membersList.innerHTML = ''; // Clear the current list

      data.members.forEach(member => {
        const memberItem = document.createElement('li');
        memberItem.classList.add('mb-4', 'p-4', 'bg-gray-100', 'rounded-lg', 'shadow');
        memberItem.innerHTML = `
                  <h3 class="text-lg font-bold">${member.userDetails ? member.userDetails.name : 'Name not available'}</h3>
                  <p class="text-gray-600">${member.userDetails ? member.userDetails.email : 'Email not available'}</p>
              `;
        membersList.appendChild(memberItem);
      });

      // Update the pagination buttons
      const paginationContainer = button.parentElement;
      paginationContainer.innerHTML = '';

      if (data.previousPageUrl) {
        const prevButton = document.createElement('button');
        prevButton.id = 'prev-page-members';
        prevButton.classList.add('bg-primary', 'text-white', 'px-4', 'py-2', 'rounded-full', 'shadow-md');
        prevButton.setAttribute('data-prev-page', data.currentPage - 1);
        prevButton.setAttribute('data-group-id', groupId);
        prevButton.textContent = 'Previous';
        prevButton.addEventListener('click', handleLoadMoreMembers);
        paginationContainer.appendChild(prevButton);
      }

      if (data.nextPageUrl) {
        const nextButton = document.createElement('button');
        nextButton.id = 'next-page-members';
        nextButton.classList.add('bg-primary', 'text-white', 'px-4', 'py-2', 'rounded-full', 'shadow-md');
        nextButton.setAttribute('data-next-page', data.currentPage + 1);
        nextButton.setAttribute('data-group-id', groupId);
        nextButton.textContent = 'Next';
        nextButton.addEventListener('click', handleLoadMoreMembers);
        paginationContainer.appendChild(nextButton);
      }
    })
    .catch(error => console.error('Error:', error));
}


function handleViewNotification() {
  const notificationId = this.closest('li').getAttribute('data-notification-id');
  sendAjaxRequest(
    'POST',
    `/notifications/${notificationId}/mark-as-viewed`,
    {},
    function () {
      if (this.status === 200) {
        const response = JSON.parse(this.responseText);
        if (response.success) {
          document.querySelector(`li[data-notification-id="${notificationId}"]`).remove();
          showStatusMessage(true, "Notification viewed");

          const userIdMeta = document.querySelector('meta[name="user-id"]');
          updateNotificationCount(userIdMeta);

          // Check if there are any remaining notifications
          const remainingNotifications = document.querySelectorAll('ul.follow-requests-list li');
          if (remainingNotifications.length === 0) {
            document.querySelector('ul.follow-requests-list').innerHTML = '<p class="text-gray-600">You have no unread notifications.</p>';
          }
        } else {
          showStatusMessage(false, "Failed to view notification");
        }
      } else {
        showStatusMessage(false, "Failed to view notification");
      }
    }
  );
}


function setupPusherNotifications(userIdMeta, pusher) {
  const userId = userIdMeta.getAttribute('content');

  // Subscribe to the user's notification channel
  const channel = pusher.subscribe(`notifications-${userId}`);
  channel.bind('notification', function (data) {
    updateNotificationCount(userIdMeta);
    showNotification(data.notification);
  });
}

function updateNotificationCount(userIdMeta) {
  const userId = userIdMeta.getAttribute('content');
  fetch(`/notifications/count/${userId}`)
    .then(response => response.json())
    .then(data => {
      const notificationCountElement = document.getElementById('notification-count');
      if (data.count > 0) {
        notificationCountElement.textContent = data.count;
        notificationCountElement.classList.remove('hidden');
      } else {
        notificationCountElement.classList.add('hidden');
      }
    })
    .catch(error => console.error('Error fetching notification count:', error));
}

function showNotification(message) {
  // Create the notification element
  const notification = document.createElement('div');

  // Add Tailwind CSS classes for styling
  notification.classList.add(
    'fixed',
    'text-white',
    'py-6', // Increased padding for a bigger popup
    'px-12',
    'rounded-lg',
    'shadow-lg',
    'text-sm',
    'font-bold',
    'z-50',
    'cursor-pointer',
    'opacity-0',
    'transition',
    'duration-500',
    'ease-in-out'
  );

  // Set inline styles for positioning
  notification.style.top = '10%';
  notification.style.right = '7%';
  notification.style.position = 'fixed';
  notification.style.backgroundColor = '#1d3647';

  // Set the notification text
  notification.textContent = message;

  // Add click event to redirect to the given URL
  notification.addEventListener('click', () => {
    window.location.href = '/notifications';
  });

  // Append the notification to the body
  document.body.appendChild(notification);

  // Trigger the fade-in effect
  setTimeout(() => {
    notification.classList.remove('opacity-0');
    notification.classList.add('opacity-100');
  }, 10); // Small delay to ensure transition is applied

  // Remove the notification after 2 seconds
  setTimeout(() => {
    notification.classList.remove('opacity-100');
    notification.classList.add('opacity-0');
    setTimeout(() => {
      notification.remove();
    }, 500); // Wait for the fade-out transition to complete
  }, 5000);
}

function handlePrevPageUsers(event) {
  const button = event.target;
  const currentPage = parseInt(button.getAttribute("data-page"), 10);
  const groupId = button.getAttribute("data-group-id");

  if (currentPage > 1) {
    fetchPaginatedUsers(groupId, currentPage - 1);
  }
}

function handleNextPageUsers(event) {
  const button = event.target;
  const currentPage = parseInt(button.getAttribute("data-page"), 10);
  const groupId = button.getAttribute("data-group-id");

  fetchPaginatedUsers(groupId, currentPage + 1);
}

function fetchPaginatedUsers(groupId, page) {
  fetch(`/groups/${groupId}/users?page=${page}`)
    .then((response) => response.json())
    .then((data) => {
      const usersList = document.getElementById("all-users-list");
      usersList.innerHTML = "";

      data.users.forEach((user) => {
        const userItem = document.createElement("li");
        userItem.classList.add(
          "mb-4",
          "p-4",
          "bg-gray-100",
          "rounded-lg",
          "shadow",
          "flex",
          "justify-between",
          "items-center"
        );

        // Determine the button text and class based on invitation status
        const isInvited = user.invitationStatus === 'pending';
        const buttonText = isInvited ? 'Cancel Invite' : 'Invite';
        const buttonClass = isInvited ? 'cancel-invite-user-button' : 'invite-user-button';

        userItem.innerHTML = `
          <div>
            <h3 class="text-lg font-bold">${user.userDetails.name ?? "Name not available"}</h3>
            <p class="text-gray-600">${user.userDetails.email ?? "Email not available"}</p>
          </div>
          <button class="${buttonClass} bg-primary text-white px-4 py-2 rounded-full shadow-md"
            data-group-id="${groupId}" data-user-id="${user.id}">${buttonText}</button>
        `;
        usersList.appendChild(userItem);
      });

      const prevPageButton = document.getElementById("prev-page-users");
      const nextPageButton = document.getElementById("next-page-users");

      prevPageButton.setAttribute("data-page", data.currentPage - 1);
      nextPageButton.setAttribute("data-page", data.currentPage + 1);

      prevPageButton.disabled = !data.previousPageUrl;
      nextPageButton.disabled = !data.nextPageUrl;

      addEventListeners(); // Re-add event listeners for the new buttons
    })
    .catch((error) => console.error("Error fetching paginated users:", error));
}

function handleDeleteMember(event) {
  const button = event.target.closest(".delete-member-button");
  const groupId = button.getAttribute("data-group-id");
  const memberId = button.getAttribute("data-member-id");

  fetch(`/groups/${groupId}/members/${memberId}/remove`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.closest("li").remove();
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred while trying to remove the member.");
    });
}


function showEditDescriptionForm() {
  document.getElementById("edit-description-form").classList.remove("hidden");
  document.getElementById("edit-description-button").classList.add("hidden");
}

function hideEditDescriptionForm() {
  document.getElementById("edit-description-form").classList.add("hidden");
  document.getElementById("edit-description-button").classList.remove("hidden");
}


function handleEditDescriptionFormSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const groupId = form.getAttribute("data-group-id");
  const name = document.getElementById("name-input").value;
  const description = document.getElementById("description-input").value;

  fetch(`/groups/${groupId}/update-description`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ name: name, description: description }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, data.message);
        const groupNameElement = document.getElementById("group-name");
        const groupDescriptionElement =
          document.getElementById("group-description");
        if (groupNameElement) {
          groupNameElement.textContent = name;
        }
        if (groupDescriptionElement) {
          groupDescriptionElement.textContent = description;
        }
        hideEditDescriptionForm();
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred while trying to update the group.");
    });
}

function loadInvitations() {
  fetch("/invitations", {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const invitationsList = document.getElementById("invitations-list");
      invitationsList.innerHTML = "";

      if (data.success) {
        if (data.invitations.length === 0) {
          const noInvitationsMessage = document.createElement("p");
          noInvitationsMessage.classList.add("text-gray-600");
          noInvitationsMessage.textContent =
            "You have no invitations at the moment.";
          invitationsList.appendChild(noInvitationsMessage);
        } else {
          data.invitations.forEach((invitation) => {
            const listItem = document.createElement("li");
            listItem.classList.add(
              "mb-4",
              "p-4",
              "bg-gray-100",
              "rounded-lg",
              "shadow",
              "flex",
              "justify-between",
              "items-center"
            );
            listItem.innerHTML = `
                      <div>
                          <h3 class="text-lg font-bold">${invitation.group.name}</h3>
                          <p class="text-gray-600">${invitation.group.description}</p>
                      </div>
                      <div class="flex space-x-2">
                          <button class="accept-invitation bg-secondary text-white px-4 py-2 rounded-full shadow-md"
                              data-invitation-id="${invitation.id}">Accept</button>
                          <button class="reject-invitation bg-tertiary text-secondary px-4 py-2 rounded-full shadow-md"
                              data-invitation-id="${invitation.id}">Reject</button>
                      </div>
                  `;

            invitationsList.appendChild(listItem);
          });

          document.querySelectorAll(".accept-invitation").forEach((button) => {
            button.addEventListener("click", handleAcceptInvitation);
          });

          document.querySelectorAll(".reject-invitation").forEach((button) => {
            button.addEventListener("click", handleRejectInvitation);
          });
        }
      } else {
        showStatusMessage(false, "Failed to load invitations.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred.");
    });
}

function handleInviteUser(event) {
  const button = event.target;
  const groupId = button.getAttribute("data-group-id");
  const userId = button.getAttribute("data-user-id");

  fetch(`/groups/${groupId}/invite/${userId}`, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.textContent = "Cancel Invite";
        button.classList.remove("invite-user-button");
        button.classList.add("cancel-invite-user-button");
        button.removeEventListener("click", handleInviteUser);
        button.addEventListener("click", handleCancelInviteUser);
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred while inviting the user.");
    });
}

function handleCancelInviteUser(event) {
  const button = event.target;
  const groupId = button.getAttribute("data-group-id");
  const userId = button.getAttribute("data-user-id");

  fetch(`/groups/${groupId}/cancel-invite/${userId}`, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showStatusMessage(true, data.message);
        button.textContent = "Invite";
        button.classList.remove("cancel-invite-user-button");
        button.classList.add("invite-user-button");
        button.removeEventListener("click", handleCancelInviteUser);
        button.addEventListener("click", handleInviteUser);
      } else {
        showStatusMessage(false, data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showStatusMessage(false, "An error occurred while canceling the invitation.");
    });
}


function handleForgotPasswordFormSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, 'Email to reset password sent with success');
      } else {
        showStatusMessage(false, 'Failed to send email. Please try again.');
      }
    })
    .catch(error => {
      console.error('Error sending email:', error);
      showStatusMessage(false, 'Failed to send email. Please try again.');
    });
}

function handleResetPasswordFormSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        localStorage.setItem('status', data.message);
        window.location.href = '/login';
      } else {
        showStatusMessage(false, data.message || 'Failed to change password. Please try again.');
      }
    })
    .catch(error => {
      console.error('Error changing password:', error);
      showStatusMessage(false, 'Failed to change password. Please try again.');
    });
}

function togglePinPost(postId, action) {
  const url = action === 'pin' ? `/posts/${postId}/pin` : `/posts/${postId}/unpin`;

  fetch(url, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({})
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);

        // Update the pinned icon visibility
        const postHeader = document.querySelector(`article[data-id="${postId}"] .post-header-icons`);
        let pinnedIcon = postHeader.querySelector('.pinned_icon');
        const dropdownButton = postHeader.querySelector('.dropdown-button').parentElement;

        if (action === 'pin') {
          if (!pinnedIcon) {
            pinnedIcon = document.createElement('i');
            pinnedIcon.classList.add('fa', 'fa-thumbtack', 'text-white', 'ml-2', 'pinned_icon');
            pinnedIcon.setAttribute('title', 'Pinned');
            postHeader.insertBefore(pinnedIcon, dropdownButton);
          }
        } else {
          if (pinnedIcon) {
            pinnedIcon.remove();
          }
        }

        // Update the dropdown menu button text
        const pinButton = document.querySelector(`#dropdown-${postId} .pin-button`);
        if (pinButton) {
          pinButton.innerHTML = `<i class="fa fa-thumbtack text-2xl"></i> ${action === 'pin' ? 'Unpin' : 'Pin'}`;
          pinButton.setAttribute('onclick', `togglePinPost(${postId}, '${action === 'pin' ? 'unpin' : 'pin'}')`);
        }

        // Only perform the following actions if on the profile page
        if (window.location.pathname.includes('/profile')) {
          // Move the post to the correct position
          const postElement = document.querySelector(`article[data-id="${postId}"]`);
          const currentPostDateElement = postElement.querySelector('.exact_time');
          const postsContainer = document.getElementById('posts');
          const postsHeader = document.getElementById('posts_header_title');
          if (action === 'pin') {
            const pinnedPosts = Array.from(postsContainer.querySelectorAll('.post'))
              .filter(post => post.querySelector('.pinned_icon') && post.getAttribute('data-id') !== postId.toString());
            if (pinnedPosts.length) {
              let inserted = false;
              for (let i = 0; i < pinnedPosts.length; i++) {
                const pinnedPost = pinnedPosts[i];
                const pinnedPostDateElement = pinnedPost.querySelector('.exact_time');
                if (pinnedPostDateElement && currentPostDateElement) {
                  const pinnedPostDate = new Date(pinnedPostDateElement.textContent);
                  const currentPostDate = new Date(currentPostDateElement.textContent);
                  if (currentPostDate > pinnedPostDate) {
                    postsContainer.insertBefore(postElement, pinnedPost);
                    inserted = true;
                    break;
                  }
                }
              }
              if (!inserted) {
                postsContainer.insertBefore(postElement, pinnedPosts[pinnedPosts.length - 1].nextSibling);
              }
            } else {
              postsContainer.insertBefore(postElement, postsHeader.nextSibling);
            }
          } else {
            const unpinnedPosts = Array.from(postsContainer.querySelectorAll('.post'))
              .filter(post => !post.querySelector('.pinned_icon') && post.getAttribute('data-id') !== postId.toString());
            if (unpinnedPosts.length > 0) {
              let inserted = false;
              for (let i = 0; i < unpinnedPosts.length; i++) {
                const unpinnedPost = unpinnedPosts[i];
                const unpinnedPostDateElement = unpinnedPost.querySelector('.exact_time');
                const currentPostDateElement = postElement.querySelector('.exact_time');
                if (unpinnedPostDateElement && currentPostDateElement) {
                  const unpinnedPostDate = new Date(unpinnedPostDateElement.textContent);
                  const currentPostDate = new Date(currentPostDateElement.textContent);
                  if (currentPostDate > unpinnedPostDate) {
                    postsContainer.insertBefore(postElement, unpinnedPost);
                    inserted = true;
                    break;
                  }
                }
              }
              if (!inserted) {
                postsContainer.appendChild(postElement);
              }
            } else {
              postsContainer.appendChild(postElement);
            }
          }

          // Scroll to the new position of the post
          postElement.scrollIntoView({ behavior: 'smooth' });
        }
      } else {
        console.error(`Failed to ${action} post:`, data);
        showStatusMessage(false, `Failed to ${action} post.`);
      }
    })
    .catch(error => {
      console.error(`Error ${action}ning post:`, error);
      showStatusMessage(false, `Failed to ${action} post.`);
    });
}


function handleFollowers() {
  const followersElement = document.querySelector('.list-followers');
  const followersModal = document.getElementById('followersModal');
  const followersList = document.getElementById('followersList');
  const userId = followersElement.getAttribute('data-user-id');

  fetch(`/profile/${userId}/followers`)
      .then(response => {
          return response.text();
      })
      .then(html => {
          followersList.innerHTML = html;
          followersModal.classList.remove('hidden');
          addRemoveEventListeners(); // Add event listeners for remove buttons
      })
      .catch(error => console.error('Error fetching followers:', error));
}

function handleFollowing() {
  const followingElement = document.querySelector('.list-following');
  const followingModal = document.getElementById('followingModal');
  const followingList = document.getElementById('followingList');
  const userId = followingElement.getAttribute('data-user-id');
  fetch(`/profile/${userId}/following`)
      .then(response => {
          return response.text();
      })
      .then(html => {
          followingList.innerHTML = html;
          followingModal.classList.remove('hidden');
          addUnfollowEventListeners(); // Add event listeners for unfollow buttons
          setupInfiniteScroll(); // Setup infinite scroll after loading initial users
      })
      .catch(error => console.error('Error fetching following:', error));
}

function setupInfiniteScroll() {
  const followingList = document.getElementById('followingList');
  const loading = document.getElementById('loading');
  let offset = 6;
  let loadingMore = false;

  if (!followingList) {
      return;
  }

  followingList.addEventListener('scroll', function () {
      if (followingList.scrollTop + followingList.clientHeight >= followingList.scrollHeight && !loadingMore) {
          loadingMore = true;
          loading.classList.remove('hidden');

          const userId = document.querySelector('.list-following').getAttribute('data-user-id');
          fetch(`/profile/${userId}/following?offset=${offset}`)
              .then(response => response.json())
              .then(data => {
                  if (data.followingClients.length === 0 && data.followingRestaurants.length === 0) {
                      loading.classList.add('hidden');
                      return;
                  }

                  data.followingClients.forEach(client => {
                      const listItem = document.createElement('li');
                      listItem.classList.add('flex', 'justify-between', 'items-center', 'mb-4');
                      listItem.innerHTML = `
                          <span class="mr-4">${client.userDetails.name}</span>
                          <button class="unfollow-btn bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-followed-id="${client.id}">Unfollow</button>
                      `;
                      followingList.appendChild(listItem);
                  });

                  data.followingRestaurants.forEach(restaurant => {
                      const listItem = document.createElement('li');
                      listItem.classList.add('flex', 'justify-between', 'items-center', 'mb-4');
                      listItem.innerHTML = `
                          <span class="mr-4">${restaurant.userDetails.name}</span>
                          <button class="unfollow-btn bg-secondary text-white px-4 py-2 rounded-full shadow-md mt-2" data-followed-id="${restaurant.id}">Unfollow</button>
                      `;
                      followingList.appendChild(listItem);
                  });

                  offset += 6;
                  loadingMore = false;
                  loading.classList.add('hidden');
                  addUnfollowEventListeners(); // Re-add event listeners for new buttons
              })
              .catch(error => {
                  console.error('Error loading more following:', error);
                  loadingMore = false;
                  loading.classList.add('hidden');
              });
      }
  });
}


function addRemoveEventListeners() {
  const removeButtons = document.querySelectorAll('.remove-btn');
  removeButtons.forEach(button => {
    button.addEventListener('click', handleRemove);
  });
}

function addUnfollowEventListeners() {
  const unfollowButtons = document.querySelectorAll('.unfollow-btn');
  unfollowButtons.forEach(button => {
    button.addEventListener('click', handleUnfollow);
  });
}

function handleRemove(event) {
  const button = event.target;
  const followerId = button.getAttribute('data-follower-id');
  fetch(`/profile/${followerId}/unfollow`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        button.closest('li').remove();
      } else {
        console.error('Remove failed:', data.message);
      }
    })
    .catch(error => console.error('Error removing follower:', error));
}

function handleUnfollow(event) {
  const button = event.target;
  const followedId = button.getAttribute('data-followed-id');


  fetch(`/profile/${followedId}/unfollow`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        const listItem = button.closest('li');
        listItem.remove(); // Remove the followed user from the list

        // Decrement the following count
        const followingCountElement = document.querySelector('.following-count');
        let followingCount = parseInt(followingCountElement.textContent, 10);
        followingCountElement.textContent = followingCount - 1;

        // Check if there are no more followed users
        const followingList = document.getElementById('followingList');
        const listItems = followingList.querySelectorAll('li'); // Get only <li> elements
        if (listItems.length === 0) {
          const noFollowingMessage = document.getElementById('noFollowingMessage');
          noFollowingMessage.classList.remove('hidden');
        }
      } else {
        console.error('Unfollow failed:', data.message);
      }
    })
    .catch(error => console.error('Error unfollowing user:', error));
}


function handleAppealUnblock() {
  const appealUnblockPopupContainer = document.getElementById('appealUnblockPopupContainer');
  appealUnblockPopupContainer.classList.add('show');
  appealUnblockPopupContainer.classList.remove('hidden');

  const cancelUnblockAppealButton = document.getElementById('cancelUnblockAppealButton');
  if (cancelUnblockAppealButton) {
    cancelUnblockAppealButton.addEventListener('click', handleCancelUnblockAppeal);
  }

  const sendUnblockAppealButton = document.getElementById('sendUnblockAppealButton');
  if (sendUnblockAppealButton) {
    sendUnblockAppealButton.addEventListener('click', handleSendUnblockAppeal);
  }
}

function handleCancelUnblockAppeal() {
  const appealUnblockPopupContainer = document.getElementById('appealUnblockPopupContainer');
  appealUnblockPopupContainer.classList.remove('show');
  appealUnblockPopupContainer.classList.add('hidden');
}

function handleSendUnblockAppeal(event) {
  event.preventDefault();
  const appealUnblockPopupContainer = document.getElementById('appealUnblockPopupContainer');

  const message = document.getElementById('appealMessage').value;

  fetch('/appeal_unblock', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      message: message
    })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showStatusMessage(true, data.message);
      } else {
        showStatusMessage(false, 'Failed to send appeal unblock request.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showStatusMessage(false, 'Failed to send appeal unblock request.');
    });

  appealUnblockPopupContainer.classList.remove('show');
  appealUnblockPopupContainer.classList.add('hidden');
}