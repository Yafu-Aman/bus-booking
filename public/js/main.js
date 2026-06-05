// ── Cancel booking popup (passenger) ──
function confirmCancel(button) {
  var form = button.closest("form");
  showPopup(
    "Cancel Booking",
    "Are you sure you want to cancel this booking? This cannot be undone.",
    "Yes, Cancel",
    "Keep Booking",
    function () {
      form.submit();
    },
  );
}

// ── Delete route popup (operator) ──
function confirmDelete(routeId) {
  showPopup(
    "Delete Route",
    "Are you sure you want to delete this route? This cannot be undone.",
    "Yes, Delete",
    "Keep Route",
    function () {
      window.location.href =
        "/bus-booking/public/index.php?page=operator-delete&route_id=" +
        routeId;
    },
  );
}

// ── Delete route popup (admin) ──
function confirmAdminDelete(routeId) {
  showPopup(
    "Delete Route",
    "Are you sure? This permanently deletes the route and affects all bookings on it.",
    "Yes, Delete",
    "Cancel",
    function () {
      window.location.href =
        "/bus-booking/public/index.php?page=admin-delete-route&route_id=" +
        routeId;
    },
  );
}

// ── Suspend user popup (admin) ──
function confirmSuspend(userId, name) {
  showPopup(
    "Suspend User",
    "Are you sure you want to suspend " +
      name +
      "? They will not be able to log in.",
    "Yes, Suspend",
    "Cancel",
    function () {
      window.location.href =
        "/bus-booking/public/index.php?page=admin-suspend&user_id=" + userId;
    },
  );
}

// ── Activate user popup (admin) ──
function confirmActivate(userId, name) {
  showPopup(
    "Activate User",
    "Activate " + name + "? They will be able to log in again.",
    "Yes, Activate",
    "Cancel",
    function () {
      window.location.href =
        "/bus-booking/public/index.php?page=admin-activate&user_id=" + userId;
    },
  );
}

// ── Shared popup builder ──
function showPopup(title, message, confirmText, cancelText, onConfirm) {
  var overlay = document.createElement("div");
  overlay.className = "popup-overlay";
  overlay.id = "active-popup";
  overlay.innerHTML =
    '<div class="popup-box">' +
    "<h3>" +
    title +
    "</h3>" +
    "<p>" +
    message +
    "</p>" +
    '<div class="popup-buttons">' +
    '<button class="btn-cancel" id="popup-confirm">' +
    confirmText +
    "</button>" +
    '<button class="btn-secondary" onclick="closePopup()">' +
    cancelText +
    "</button>" +
    "</div>" +
    "</div>";
  document.body.appendChild(overlay);

  document
    .getElementById("popup-confirm")
    .addEventListener("click", function () {
      closePopup();
      onConfirm();
    });
}

function closePopup() {
  var overlay = document.getElementById("active-popup");
  if (overlay) {
    document.body.removeChild(overlay);
  }
}

function confirmReject(routeId) {
  showPopup(
    "Reject Route",
    "Are you sure you want to reject this route? It will be permanently deleted.",
    "Yes, Reject",
    "Cancel",
    function () {
      window.location.href =
        "/bus-booking/public/index.php?page=admin-reject-route&route_id=" +
        routeId;
    },
  );
}
