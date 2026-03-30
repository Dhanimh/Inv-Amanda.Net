// Confirm Delete
function confirmDelete(url, name) {
  const confirmed = confirm(`Apakah Anda yakin ingin menghapus "${name}"?\n\nData yang sudah dihapus tidak dapat dikembalikan!`);
  if (confirmed) {
    window.location.href = url;
  }
  return false;
}

// Auto hide alerts
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll('[class*="bg-"][class*="-50"]');
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease";
      alert.style.opacity = "0";
      setTimeout(() => alert.remove(), 500);
    }, 5000);
  });
});

// Format number input as currency
function formatCurrency(input) {
  let value = input.value.replace(/[^\d]/g, "");
  if (value) {
    value = parseInt(value).toLocaleString("id-ID");
  }
  input.value = value;
}

// Preview image before upload
function previewImage(input) {
  const preview = document.getElementById("imagePreview");
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.classList.remove("hidden");
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Search table
function searchTable(inputId, tableId) {
  const input = document.getElementById(inputId);
  const filter = input.value.toUpperCase();
  const table = document.getElementById(tableId);
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    let found = false;
    const td = tr[i].getElementsByTagName("td");

    for (let j = 0; j < td.length; j++) {
      if (td[j]) {
        const txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          found = true;
          break;
        }
      }
    }

    tr[i].style.display = found ? "" : "none";
  }
}

// Calculate stock based on transaction type
function updateStock() {
  const barangSelect = document.getElementById("id_barang");
  const jumlahInput = document.getElementById("jumlah");
  const stokInfo = document.getElementById("stokInfo");

  if (barangSelect && jumlahInput && stokInfo) {
    const selectedOption = barangSelect.options[barangSelect.selectedIndex];
    const stokSekarang = parseInt(selectedOption.dataset.stok || 0);
    const jumlah = parseInt(jumlahInput.value || 0);

    stokInfo.textContent = `Stok saat ini: ${stokSekarang}`;
  }
}

// Print page
function printPage() {
  window.print();
}

// Export table to CSV
function exportTableToCSV(tableId, filename) {
  const table = document.getElementById(tableId);
  let csv = [];
  const rows = table.querySelectorAll("tr");

  for (let i = 0; i < rows.length; i++) {
    const row = [],
      cols = rows[i].querySelectorAll("td, th");

    for (let j = 0; j < cols.length; j++) {
      row.push(cols[j].innerText);
    }

    csv.push(row.join(","));
  }

  downloadCSV(csv.join("\n"), filename);
}

function downloadCSV(csv, filename) {
  const csvFile = new Blob([csv], { type: "text/csv" });
  const downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

// Form validation
function validateForm(formId) {
  const form = document.getElementById(formId);
  const inputs = form.querySelectorAll("[required]");
  let valid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      input.classList.add("border-red-500");
      valid = false;
    } else {
      input.classList.remove("border-red-500");
    }
  });

  return valid;
}
