<?php

namespace Oro\Bundle\ContactUsBundle\Controller;

use Oro\Bundle\ContactUsBundle\Entity\ContactReason;
use Oro\Bundle\ContactUsBundle\Form\Type\ContactReasonType;
use Oro\Bundle\FormBundle\Model\UpdateHandler;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\CsrfProtection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * CRUD controller for ContactReason entity
 */
class ContactReasonController extends AbstractController
{
    /**
     * @Route("/", name="oro_contactus_reason_index")
     * @Template
     * @Acl(
     *      id="oro_contactus_reason_view",
     *      type="entity",
     *      class="OroContactUsBundle:ContactReason",
     *      permission="VIEW"
     * )
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => ContactReason::class
        ];
    }

    /**
     * @return array|RedirectResponse
     *
     * @Route("/create", name="oro_contactus_reason_create")
     * @Template("@OroContactUs/ContactReason/update.html.twig")
     * @Acl(
     *      id="oro_contactus_reason_create",
     *      type="entity",
     *      class="OroContactUsBundle:ContactReason",
     *      permission="CREATE"
     * )
     */
    public function createAction()
    {
        return $this->update(new ContactReason());
    }

    /**
     * @ParamConverter("contactReason", options={"repository_method" = "getContactReason"})
     *
     * @param ContactReason $contactReason
     * @return array
     *
     * @Route("/update/{id}", name="oro_contactus_reason_update", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="oro_contactus_reason_update",
     *      type="entity",
     *      class="OroContactUsBundle:ContactReason",
     *      permission="EDIT"
     * )
     */
    public function updateAction(ContactReason $contactReason)
    {
        return $this->update($contactReason);
    }

    /**
     * @param ContactReason $contactReason
     *
     * @return array|RedirectResponse
     */
    protected function update(ContactReason $contactReason)
    {
        return $this->get(UpdateHandler::class)->update(
            $contactReason,
            $this->createForm(ContactReasonType::class, $contactReason),
            $this->get(TranslatorInterface::class)->trans('oro.contactus.contactreason.saved')
        );
    }

    /**
     * @ParamConverter("contactReason", options={"repository_method" = "getContactReason"})
     *
     * @param ContactReason $contactReason
     * @return JsonResponse
     *
     * @Route("/delete/{id}", name="oro_contactus_reason_delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * @Acl(
     *      id="oro_contactus_reason_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="OroContactUsBundle:ContactReason"
     * )
     * @CsrfProtection()
     */
    public function deleteAction(ContactReason $contactReason)
    {
        $em = $this->get('doctrine')->getManagerForClass(ContactReason::class);
        $em->remove($contactReason);
        $em->flush();

        return new JsonResponse('', Response::HTTP_OK);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                TranslatorInterface::class,
                UpdateHandler::class,
            ]
        );
    }
}
