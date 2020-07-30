import getOppositePlacement from '../utils/getOppositePlacement';
import getOppositeVariation from '../utils/getOppositeVariation';
import getPopperOffsets from '../utils/getPopperOffsets';
import runModifiers from '../utils/runModifiers';
import getBoundaries from '../utils/getBoundaries';
import isModifierEnabled from '../utils/isModifierEnabled';
import clockwise from '../utils/clockwise';

const BEHAVIORS = {
  FLIP: 'flip',
  CLOCKWISE: 'clockwise',
  COUNTERCLOCKWISE: 'counterclockwise',
};

/**
 * @function
 * @memberof Modifiers
 * @argument {Object} data - The data object generated by update method
 * @argument {Object} options - Modifiers configuration and options
 * @returns {Object} The data object, properly modified
 */
export default function flip(data, options) {
  // if `inner` modifier is enabled, we can't use the `flip` modifier
  if (isModifierEnabled(data.instance.modifiers, 'inner')) {
    return data;
  }

  if (data.flipped && data.placement === data.originalPlacement) {
    // seems like flip is trying to loop, probably there's not enough space on any of the flippable sides
    return data;
  }

  const boundaries = getBoundaries(
    data.instance.popper,
    data.instance.reference,
    options.padding,
    options.boundariesElement
  );

  let placement = data.placement.split('-')[0];
  let placementOpposite = getOppositePlacement(placement);
  let variation = data.placement.split('-')[1] || '';

  let flipOrder = [];

  switch (options.behavior) {
    case BEHAVIORS.FLIP:
      flipOrder = [placement, placementOpposite];
      break;
    case BEHAVIORS.CLOCKWISE:
      flipOrder = clockwise(placement);
      break;
    case BEHAVIORS.COUNTERCLOCKWISE:
      flipOrder = clockwise(placement, true);
      break;
    default:
      flipOrder = options.behavior;
  }

  flipOrder.forEach((step, index) => {
    if (placement !== step || flipOrder.length === index + 1) {
      return data;
    }

    placement = data.placement.split('-')[0];
    placementOpposite = getOppositePlacement(placement);

    const popperOffsets = data.offsets.popper;
    const refOffsets = data.offsets.reference;

    // using floor because the reference offsets may contain decimals we are not going to consider here
    const floor = Math.floor;
    const overlapsRef =
      (placement === 'left' &&
        floor(popperOffsets.right) > floor(refOffsets.left)) ||
      (placement === 'right' &&
        floor(popperOffsets.left) < floor(refOffsets.right)) ||
      (placement === 'top' &&
        floor(popperOffsets.bottom) > floor(refOffsets.top)) ||
      (placement === 'bottom' &&
        floor(popperOffsets.top) < floor(refOffsets.bottom));

    const overflowsLeft = floor(popperOffsets.left) < floor(boundaries.left);
    const overflowsRight = floor(popperOffsets.right) > floor(boundaries.right);
    const overflowsTop = floor(popperOffsets.top) < floor(boundaries.top);
    const overflowsBottom =
      floor(popperOffsets.bottom) > floor(boundaries.bottom);

    const overflowsBoundaries =
      (placement === 'left' && overflowsLeft) ||
      (placement === 'right' && overflowsRight) ||
      (placement === 'top' && overflowsTop) ||
      (placement === 'bottom' && overflowsBottom);

    // flip the variation if required
    const isVertical = ['top', 'bottom'].indexOf(placement) !== -1;
    const flippedVariation =
      !!options.flipVariations &&
      ((isVertical && variation === 'start' && overflowsLeft) ||
        (isVertical && variation === 'end' && overflowsRight) ||
        (!isVertical && variation === 'start' && overflowsTop) ||
        (!isVertical && variation === 'end' && overflowsBottom));

    if (overlapsRef || overflowsBoundaries || flippedVariation) {
      // this boolean to detect any flip loop
      data.flipped = true;

      if (overlapsRef || overflowsBoundaries) {
        placement = flipOrder[index + 1];
      }

      if (flippedVariation) {
        variation = getOppositeVariation(variation);
      }

      data.placement = placement + (variation ? '-' + variation : '');

      // this object contains `position`, we want to preserve it along with
      // any additional property we may add in the future
      data.offsets.popper = {
        ...data.offsets.popper,
        ...getPopperOffsets(
          data.instance.popper,
          data.offsets.reference,
          data.placement
        ),
      };

      data = runModifiers(data.instance.modifiers, data, 'flip');
    }
  });
  return data;
}
