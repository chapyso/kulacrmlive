/**
 * KulaCRM Field Productivity Module
 * Handles camera capture, QR/barcode scanning, and GPS location tagging for field operations.
 */
(function(window, document) {
  'use strict';

  const KulaField = {
    /**
     * Trigger Camera Capture for Livestock / Feed Photos
     */
    capturePhoto: function(fileInputId, previewImgId) {
      const fileInput = document.getElementById(fileInputId);
      if (!fileInput) return;

      fileInput.setAttribute('accept', 'image/*');
      fileInput.setAttribute('capture', 'environment');
      fileInput.click();

      fileInput.onchange = function(e) {
        const file = e.target.files[0];
        if (file && previewImgId) {
          const preview = document.getElementById(previewImgId);
          if (preview) {
            const reader = new FileReader();
            reader.onload = function(evt) {
              preview.src = evt.target.result;
              preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
          }
        }
      };
    },

    /**
     * Get Current GPS Location for Field Tagging
     */
    getGPSLocation: function(callback) {
      if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const loc = {
              lat: position.coords.latitude,
              lng: position.coords.longitude,
              accuracy: position.coords.accuracy
            };
            if (typeof callback === 'function') callback(null, loc);
          },
          function(error) {
            if (typeof callback === 'function') callback(error, null);
          },
          { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
      } else {
        if (typeof callback === 'function') callback(new Error('Geolocation not supported'), null);
      }
    },

    /**
     * Scanner Prompt Helper
     */
    scanBarcode: function(targetInputId) {
      const input = document.getElementById(targetInputId);
      if (!input) return;
      const code = prompt('Scan or enter Barcode/Tag ID:');
      if (code) {
        input.value = code.trim();
        input.dispatchEvent(new Event('change'));
      }
    }
  };

  window.KulaField = KulaField;
})(window, document);
