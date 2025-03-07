$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#loyaltyTable').DataTable({
        "paging": true,
        "searching": true
    });
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#hackathonTable').DataTable({
        "paging": true,
        "searching": true
    });
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#challengeTable').DataTable({
        "paging": true,
        "searching": true
    });
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#hackathonJoinTable').DataTable({
        "paging": true,
        "searching": true
    });
});

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('#challengeJoinTable').DataTable({
        "paging": true,
        "searching": true
    });
});
// $(document).ready(function () {
//     $('#hackathonTable').DataTable({
//         responsive: true, // Enable responsive feature
//         paging: true, // Enable pagination
//         searching: true, // Enable search
//         ordering: true, // Enable sorting
//         order: [[0, 'asc']], // Default order by first column
//         lengthMenu: [5, 10, 25, 50], // Options for number of records per page
//         language: {
//             search: "Filter records:", // Custom search label
//             lengthMenu: "Show _MENU_ entries", // Custom length menu label
//             info: "Showing _START_ to _END_ of _TOTAL_ entries", // Custom info label
//         }
//     });
// });

// $(document).ready(function () {
//     $('#challengeTable').DataTable({
//         responsive: true, // Enable responsive feature
//         paging: true, // Enable pagination
//         searching: true, // Enable search
//         ordering: true, // Enable sorting
//         order: [[0, 'asc']], // Default order by first column
//         lengthMenu: [5, 10, 25, 50], // Options for number of records per page
//         language: {
//             search: "Filter records:", // Custom search label
//             lengthMenu: "Show _MENU_ entries", // Custom length menu label
//             info: "Showing _START_ to _END_ of _TOTAL_ entries", // Custom info label
//         }
//     });
// });
$(document).ready(function () {
    // $('[data-toggle="tooltip"]').tooltip();
    $('#userTable').DataTable({
        "paging": true,
        "searching": true
    });
});