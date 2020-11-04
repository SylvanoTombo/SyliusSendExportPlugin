$(function ($) {
   let $exportButton = $('#export-btn');
   let $exportModal = $('#export-modal');
   let $exportType = $('#export_export_type');
   let $exportFormats = $('#export_export_format');

   const updateExportFormatField = function ($oldFormatField, selectedGabaritValue)
   {
      let gabarits = $exportFormats.data('gabarits');
      let selectedGabarit = gabarits[selectedGabaritValue];
      let formats = selectedGabarit.format;
      let $newExportFormats = $exportFormats.empty();

      formats.map(function (format) {
         let option = new Option(format, format);
         $newExportFormats.append(option);
      })
   }

   updateExportFormatField($exportFormats, $exportType.val());

   $exportButton.on('click', function (e) {
      e.stopPropagation();
      e.preventDefault();

      $exportModal.modal('show')
   });

   $exportType.on('change', function () {
      updateExportFormatField($exportFormats, this.value);
   });
});
