/**
 * Renders a canvas with an image, custom text, and optional circle overlay.
 *
 * @param {fabric.Canvas} canvas - The canvas object to render on.
 * @param {HTMLImageElement} imgElement - The image element to render on the canvas.
 * @param {Object} settings - The settings for customizing the rendering.
 * @param {string} [settings.customText=''] - The custom text to display on the canvas.
 * @param {number} [settings.xCoordinate=imgElement.width / 2] - The x-coordinate of the text position.
 * @param {number} [settings.yCoordinate=imgElement.height / 2] - The y-coordinate of the text position.
 * @param {number} [settings.circleWidth=200] - The width of the circle overlay.
 * @param {number} [settings.fontSize=50] - The font size of the text.
 * @param {string} [settings.fontColor='#000000'] - The color of the text.
 * @param {string} [settings.fontFamily='Open Sans'] - The font family of the text.
 * @param {string} [settings.circleColor='#FFFFFF'] - The color of the circle overlay.
 * @param {Object} [options={ showCircle: true }] - The options for rendering.
 * @param {boolean} [options.showCircle=true] - Whether to show the circle overlay or not.
 * @param {number} initialCanvasWidth - The initial width of the canvas.
 * @param {number} initialCanvasHeight - The initial height of the canvas.
 * @returns {void}
 */
function renderCanvas(canvas, imgElement, settings, options = { showCircle: true }, initialCanvasWidth, initialCanvasHeight) {
    // Calculate the scaling factors for the canvas based on the initial image dimensions
    const scaleX = initialCanvasWidth ? initialCanvasWidth / imgElement.width : canvas.width / imgElement.width;
    const scaleY = initialCanvasHeight ? initialCanvasHeight / imgElement.height : canvas.height / imgElement.height;

    // Extract the settings values or use default values if not provided
    const text = settings.customText || '';
    const x = parseInt(settings.xCoordinate, 10) || imgElement.width / 2;
    const y = parseInt(settings.yCoordinate, 10) || imgElement.height / 2;
    const circleWidth = parseInt(settings.circleWidth, 10) || 200;
    const fontSize = parseInt(settings.fontSize, 10) || 50;
    const fontColor = settings.fontColor || '#000000';
    const fontFamily = settings.fontFamily || 'Open Sans';
    const circleColor = settings.circleColor || '#FFFFFF';

    // Remove any existing text or path objects from the canvas
    canvas.getObjects().forEach((obj) => {
        if (obj.type === 'text' || obj.type === 'path') {
            canvas.remove(obj);
        }
    });

    // Calculate the scaled coordinates for the text position
    const scaledX = x * scaleX;
    const scaledY = y * scaleY;

    // Calculate the path data for the circle overlay
    const radius = circleWidth / 2;
    const pathData = `M ${scaledX - radius}, ${scaledY} a ${radius},${radius} 0 1,0 ${circleWidth},0`;

    // Create a half circle path object with the specified properties
    const halfCirclePath = new fabric.Path(pathData, {
        fill: '',
        stroke: circleColor,
        selectable: false,
        originX: 'center',
        left: scaledX,
        top: scaledY,
        opacity: options.showCircle ? 1 : 0,
    });

    // Calculate the path segments info for the half circle path
    const pathInfo = fabric.util.getPathSegmentsInfo(halfCirclePath.path);
    halfCirclePath.segmentsInfo = pathInfo;

    // Create a text object with the specified properties
    const textInstance = new fabric.Text(text, {
        fontSize: fontSize,
        fill: fontColor,
        textAlign: 'center',
        originX: 'center',
        path: halfCirclePath,
        pathSide: 'left',
        pathAlign: 'center',
        pathOffset: radius,
    });

    // Set the position of the text object
    textInstance.set({
        left: scaledX,
        top: scaledY,
    });

    // Update the coordinates of the text object
    textInstance.setCoords();

    // Add the half circle path and text object to the canvas
    canvas.add(halfCirclePath);

    // Load the font and then add the text object to the canvas
    loadAndUse(fontFamily, canvas, textInstance);
}

function loadAndUse(font, canvas, textInstance) {
    const myfont = new FontFaceObserver(font);
    myfont.load()
        .then(function() {
            // When font is loaded, use it.
            textInstance.set("fontFamily", font);
            canvas.add(textInstance);
            console.log(font + ' is available');
            canvas.requestRenderAll();
        }).catch(function(e) {
            console.log(e);
            console.error('Font loading failed: ' + font);
        });
}