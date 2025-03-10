import benefit from '../../../public/assets/images/benefits.svg'

const WhyCard = ({ data }) => {
    return <>
        <div className='flex bg-white p-4 rounded-lg w-[46%] mt-8 transition-transform duration-500 ease-in-out hover:scale-105'>
            <div>
                <img src={benefit} alt="Icon" />
            </div>
            <div className='mt-4 mx-10'>
                <h2 className='font-semibold text-2xl'>{data.title}</h2>
                <p>{data.desc}</p>
            </div>
        </div>
    </>
}

export default WhyCard