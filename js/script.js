/* document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById("select-all-plugins");
    const pluginCheckboxes = document.querySelectorAll('input[name="plugins[]"]');

    selectAllCheckbox.addEventListener("change", function() {
        pluginCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

  // Guides
  const buttons = document.querySelectorAll('.tab-button');
  const contents = document.querySelectorAll('.tab-content');
  
  if (buttons.length > 0 && contents.length > 0) {  // Kontrollér, at begge findes
      buttons.forEach(button => {
          button.addEventListener('click', function () {
              // Fjern aktiv status fra alle knapper og indhold
              buttons.forEach(btn => btn.classList.remove('active'));
              contents.forEach(content => content.classList.remove('active'));

              // Tilføj aktiv status til den valgte knap og tilhørende indhold
              this.classList.add('active');
              document.getElementById('tab-' + this.dataset.tab).classList.add('active');
          });
      });
  }
       
});
 */

document.addEventListener("DOMContentLoaded", function() {
    const selectAllCheckbox = document.getElementById("select-all-plugins");
    const pluginCheckboxes = document.querySelectorAll('input[name="plugins[]"]');

    if (selectAllCheckbox) {  // Tjek om elementet findes
        selectAllCheckbox.addEventListener("change", function() {
            pluginCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    } else {
        console.log("Elementet '#select-all-plugins' blev ikke fundet.");
    }

    // Guides
    const buttons = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
  
    if (buttons.length > 0 && contents.length > 0) {  // Kontrollér, at begge findes
        console.log("Fandt " + buttons.length + " knapper og " + contents.length + " indholdselementer.");
        
        buttons.forEach(button => {
            button.addEventListener('click', function () {
                // Fjern aktiv status fra alle knapper og indhold
                buttons.forEach(btn => btn.classList.remove('active'));
                contents.forEach(content => content.classList.remove('active'));

                // Tilføj aktiv status til den valgte knap og tilhørende indhold
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
            });
        });
    } else {
        console.log("Knapper eller indhold blev ikke fundet.");
    }
});
