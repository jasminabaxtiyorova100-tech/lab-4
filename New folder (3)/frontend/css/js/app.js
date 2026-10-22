
// Frontend JavaScript: communicates with the PHP backend

const API = "../backend";

// Hide the logo if the image file is missing
document.querySelectorAll(".logo").forEach((img) => {
    img.addEventListener("error", () => {
        img.style.display = "none";
    });
});

// ---------- Home Page: Check Backend ----------
const status = document.getElementById("server-status");

if (status) {
    fetch(API + "/get_students.php")
        .then((response) => response.json())
        .then((data) => {
            status.textContent =
                "Backend connected ✔ (" +
                data.count +
                " students saved)";

            status.className = "ok";
        })
        .catch(() => {
            status.textContent =
                "Backend not reachable – run with a PHP server (see README)";

            status.className = "bad";
        });
}

// ---------- Form Page: Send Data to Backend ----------
const form = document.getElementById("studentForm");

if (form) {
    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const msg = document.getElementById("msg");

        fetch(API + "/submit.php", {
            method: "POST",
            body: new FormData(form)
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    msg.className = "success";

                    msg.innerHTML =
                        "<b>" +
                        data.message +
                        "</b> <a href='table.html'>View table →</a>";

                    form.reset();
                } else {
                    msg.className = "error";

                    msg.innerHTML = data.errors
                        .map((error) => "<p>• " + error + "</p>")
                        .join("");
                }
            })
            .catch(() => {
                msg.className = "error";

                msg.textContent =
                    "Could not reach the backend. Start a PHP server (see README).";
            });
    });
}

// ---------- Table Page: Load Student Data ----------
const tableBody = document.getElementById("tableBody");

if (tableBody) {
    fetch(API + "/get_students.php")
        .then((response) => response.json())
        .then((data) => {
            if (data.students.length === 0) {
                tableBody.innerHTML =
                    "<tr><td colspan='7'>No students yet.</td></tr>";

                document.getElementById("average").textContent = "0";
                return;
            }

            tableBody.innerHTML = data.students
                .map(
                    (student, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${student.enrollment}</td>
                        <td>${student.name}</td>
                        <td>${student.course}</td>
                        <td>${student.gender}</td>
                        <td>${student.marks}</td>
                        <td>${student.grade}</td>
                    </tr>
                `
                )
                .join("");

            document.getElementById("average").textContent =
                data.average;
        })
        .catch(() => {
            tableBody.innerHTML =
                "<tr><td colspan='7'>Could not load data. Start a PHP server (see README).</td></tr>";
        });
}