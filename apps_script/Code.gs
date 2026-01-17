const PRESENTATION_ID = "";

function buildDefenseDeck() {
  var presentation = getOrCreatePresentation_();
  resetSlides_(presentation);

  var titles = [
    "Portada",
    "Resumen Ejecutivo",
    "Ruta de la Exposición",
    "¿Qué Evaluamos? (Definición Operativa)",
    "Unidad de Análisis (Santa María II)",
    "El Enfoque 6D: Definición y Justificación",
    "Matriz 6D: Dimensiones, Contenido y Peso",
    "Consecuencia de la Falla: La Cadena Causal",
    "Por qué este caso es un “Test de Estrés”",
    "Por qué el VIS Importa (Magnitud y Justificación)",
    "Cómo Evaluamos (La regla replicable)",
    "Cómo se Puntúa (Umbral + Ponderaciones)",
    "Urbana: Integración Funcional (Objetivo vs Observado)",
    "Social: Riesgo de Segregación/Aislamiento",
    "Escenarios (La Palanca de la Decisión)",
    "Resultado Integral 6D (Tabla de Viabilidad)",
    "Servicios: Equidad de Acceso (Evidencia Territorial)",
    "Normativa y Planificación (Compatibilidad Real)",
    "Síntesis de Hallazgos (Top 3)",
    "Decisión: Comparación E1–E4 (Síntesis de Robustez)",
    "Escenarios: Los Números que Deciden",
    "Conclusión: Viable Si…"
  ];

  titles.forEach(function(title, index) {
    var slide = presentation.appendSlide(SlidesApp.PredefinedLayout.BLANK);
    addTitle_(slide, title);

    switch (index + 1) {
      case 2:
        addCard_(slide, 20, 90, 200, 120, "Tarjeta 1", "Texto placeholder");
        addCard_(slide, 250, 90, 200, 120, "Tarjeta 2", "Texto placeholder");
        addCard_(slide, 20, 230, 200, 120, "Tarjeta 3", "Texto placeholder");
        addCard_(slide, 250, 230, 200, 120, "Tarjeta 4", "Texto placeholder");
        break;
      case 6:
        addCycle6D_(slide);
        break;
      case 8:
        addCausalChain_(slide);
        break;
      case 11:
        addProcessSteps_(slide);
        break;
      case 13:
        addTable_(slide, 2, 4, 20, 120, 680, 200, "Objetivo", "Observado");
        break;
      case 15:
      case 20:
      case 21:
        addTable_(slide, 4, 4, 20, 120, 680, 250, "E1", "E4");
        break;
      case 19:
        addTable_(slide, 3, 3, 20, 120, 680, 200, "Ranking", "Top 3");
        break;
      case 22:
        addBulletList_(slide, 20, 120, 680, 250, 5);
        break;
      default:
        addMainPlaceholder_(slide);
        break;
    }

    setSpeakerNotes_(slide, "GUION: FALTA\nFUENTES: FALTA");
  });
}

function getOrCreatePresentation_() {
  if (PRESENTATION_ID) {
    return SlidesApp.openById(PRESENTATION_ID);
  }
  return SlidesApp.create("Defense Deck");
}

function resetSlides_(presentation) {
  var slides = presentation.getSlides();
  for (var i = slides.length - 1; i >= 0; i--) {
    presentation.removeSlide(slides[i]);
  }
}

function addTitle_(slide, titleText) {
  var titleBox = slide.insertTextBox(titleText, 20, 20, 680, 40);
  titleBox.getText().getTextStyle().setFontSize(24).setBold(true);
}

function addMainPlaceholder_(slide) {
  var box = slide.insertShape(SlidesApp.ShapeType.RECTANGLE, 40, 120, 640, 300);
  box.getText().setText("Contenido principal (placeholder)");
}

function addCard_(slide, x, y, width, height, header, body) {
  var card = slide.insertShape(SlidesApp.ShapeType.RECTANGLE, x, y, width, height);
  var text = card.getText();
  text.setText(header + "\n" + body);
  text.getTextStyle().setFontSize(12);
}

function addTable_(slide, rows, cols, x, y, width, height, headerLeft, headerRight) {
  var table = slide.insertTable(rows, cols, x, y, width, height);
  for (var r = 0; r < rows; r++) {
    for (var c = 0; c < cols; c++) {
      var cell = table.getCell(r, c);
      if (r === 0) {
        cell.getText().setText(headerLeft + " " + (c + 1));
      } else if (r === 1 && rows === 2) {
        cell.getText().setText(headerRight + " " + (c + 1));
      } else {
        cell.getText().setText("Placeholder");
      }
    }
  }
}

function addArrow_(slide, x, y, width, height) {
  return slide.insertShape(SlidesApp.ShapeType.LINE_ARROW, x, y, width, height);
}

function setSpeakerNotes_(slide, notesText) {
  slide.getNotesPage().getSpeakerNotesShape().getText().setText(notesText);
}

function addCycle6D_(slide) {
  var labels = ["D1", "D2", "D3", "D4", "D5", "D6"];
  var positions = [
    { x: 300, y: 120 },
    { x: 420, y: 170 },
    { x: 420, y: 270 },
    { x: 300, y: 320 },
    { x: 180, y: 270 },
    { x: 180, y: 170 }
  ];
  for (var i = 0; i < labels.length; i++) {
    var circle = slide.insertShape(SlidesApp.ShapeType.ELLIPSE, positions[i].x, positions[i].y, 80, 80);
    circle.getText().setText(labels[i]);
  }
}

function addCausalChain_(slide) {
  var boxes = [];
  boxes.push(slide.insertShape(SlidesApp.ShapeType.RECTANGLE, 40, 150, 160, 60));
  boxes.push(slide.insertShape(SlidesApp.ShapeType.RECTANGLE, 250, 150, 160, 60));
  boxes.push(slide.insertShape(SlidesApp.ShapeType.RECTANGLE, 460, 150, 160, 60));
  boxes.forEach(function(box, index) {
    box.getText().setText("Causa " + (index + 1));
  });
  addArrow_(slide, 200, 175, 50, 0);
  addArrow_(slide, 410, 175, 50, 0);
}

function addProcessSteps_(slide) {
  var startX = 40;
  for (var i = 0; i < 4; i++) {
    var box = slide.insertShape(SlidesApp.ShapeType.RECTANGLE, startX + i * 160, 160, 140, 60);
    box.getText().setText("Paso " + (i + 1));
    if (i < 3) {
      addArrow_(slide, startX + 140 + i * 160, 190, 20, 0);
    }
  }
}

function addBulletList_(slide, x, y, width, height, count) {
  var box = slide.insertTextBox("", x, y, width, height);
  var text = [];
  for (var i = 0; i < count; i++) {
    text.push("Condición " + (i + 1));
  }
  box.getText().setText(text.join("\n"));
  box.getText().getListStyle().applyListPreset(SlidesApp.ListPreset.BULLET_DISC_CIRCLE_SQUARE);
}
